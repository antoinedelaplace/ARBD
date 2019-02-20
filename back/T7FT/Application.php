<?php

namespace T7FT;

use Silex;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JDesrosiers\Silex\Provider\CorsServiceProvider;
use PhpAmqpLib\Connection\AMQPStreamConnection;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use T7FT\Services\ClientService;
use T7FT\Services\RepasService;
use T7FT\Services\CommandeService;

class Application extends Silex\Application
{
	public function __construct()
	{
		parent::__construct();

		/*
        ** TODO: gestion environnement prod/dev
        */
		$isDevMode = true;

		$app['debug'] = true;

		$this
			->register(new Silex\Provider\ServiceControllerServiceProvider())
			->register(new Silex\Provider\HttpFragmentServiceProvider())
			->register(new Silex\Provider\TwigServiceProvider())
			->register(new Silex\Provider\ValidatorServiceProvider())
			->registerMiddleware()
			->registerCors()
			->registerRoutes()
			->registerEvent()
            ->registerRabbitMQ()
			->registerDb($isDevMode)
			->registerRepository()
			->registerConsole()
			->registerService()
		;
	}

	protected function registerCors()
	{
		$this->register(new CorsServiceProvider(), [
			"cors.allowOrigin" => "*"
		]);

		return $this;
	}

	protected function registerRoutes()
	{
		// Recherche tous les controllers pour les loader dans $this
		foreach (glob(__DIR__ . '/Controllers/*.php') as $controller) {
			$controllerName = pathinfo($controller)['filename'];
			$className = "\\T7FT\\Controllers\\{$controllerName}";

			// Si la class existe et qu'elle herite bien de l'interface d'un controlleur, on l'ajoute
			if (class_exists($className)
				&& in_array('Silex\Api\ControllerProviderInterface', class_implements($className))) {
				$this[$controllerName] = function () use ($className) {
					return new $className();
				};
				$this->mount('/', $this[$controllerName]);
			}
		}

		return $this;
	}

	protected function registerRepository()
	{
		// Recherche tous les controllers pour les loader dans $this
		foreach (glob(__DIR__ . '/Repository/*.php') as $repository) {
			$repositoryName = pathinfo($repository)['filename'];
			$className = "\\T7FT\\Repository\\{$repositoryName}";
			// Si la class existe et qu'elle herite bien de l'interface d'un controlleur, on l'ajoute
			if (class_exists($className)
				&& in_array('Doctrine\Common\Persistence\ObjectRepository', class_implements($className))) {
				$app = $this;
				$entityName = str_replace('Repository', '', $repositoryName);
				$entityClassName = "T7FT\\Entity\\{$entityName}";
				$this[$entityName] = function () use ($className, $app, $entityClassName) {
					return new $className($app['entityManager'], $app['entityManager']->getClassMetadata($entityClassName));
				};
			}
		}

		return $this;
	}


	protected function registerEvent()
	{
		$this->before(function (Request $request) {
			if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
				$data = json_decode($request->getContent(), true);
				$request->request->replace(is_array($data) ? $data : []);
			}
		});

		return $this;
	}

	protected function registerMiddleware()
	{
		// Recherche tous les middleware pour les loader dans $this
		foreach (glob(__DIR__ . '/Middleware/*.php') as $middleware) {
			$middleware = pathinfo($middleware)['filename'];
			$className = "\\T7FT\\Middleware\\{$middleware}";
			if (class_exists($className)
				&& in_array('Pimple\ServiceProviderInterface', class_implements($className))) {
				$this->register(new $className());
			}
		}

		return $this;
	}

	protected function registerDb($isDevMode)
	{
		$connection = [
			'dbname'   => 'speedbouffe',
			'user'     => 'bx',
			'password' => 'toto',
			'host'     => 'HAPROXY',
			'driver'   => 'pdo_mysql',
			'driverOptions' => array(
				1002 => 'SET NAMES utf8'
			)

		];

		$config = Setup::createXMLMetadataConfiguration(
			[ __DIR__ . "/Entity" ],
			$isDevMode
		);

		$app = $this;

		$this['meta'] = function () use ($isDevMode) {
			return Setup::createXMLMetadataConfiguration(
				[ __DIR__ . "/Entity" ],
				$isDevMode
			);
		};
		$config = new \Doctrine\ORM\Configuration();
		$namespaces = array(
			'/var/www/html/' => 'T7FT'
		);
		$driver = new \Doctrine\ORM\Mapping\Driver\XmlDriver(array(__DIR__ . '/Entity'));
		$config->setMetadataDriverImpl($driver);
		$config->setProxyDir('/tmp');
		$config->setProxyNamespace('TDLF\Proxies');

		$this['entityManager'] = function ($app) use ($connection, $app, $config) {
			return EntityManager::create($connection, $config);
		};

		//Ajout Type Enum
		$conn = $this['entityManager']->getConnection();
		$conn->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

		return $this;
	}

    protected function registerRabbitMQ()
    {
        $this['rabbit.connection'] = function ($app) {
            return new AMQPStreamConnection(
                'RABBITMQ',
                5672,
                'guest',
                'guest',
                '/'
            );
        };

        return $this;
    }

	protected function registerConsole()
	{
		$this->register(new \Knp\Provider\ConsoleServiceProvider(), [
			'console.name' => 'T7FT',
			'console.version' => '1.0.0',
			'console.project_directory' => __DIR__. '/..'
		]);

		return $this;
	}

	protected function registerService()
	{
		$this['ClientService'] = function ($app) {
			return new ClientService($app);
		};
		$this['RepasService'] = function ($app) {
			return new RepasService($app);
		};
		$this['CommandeService'] = function ($app) {
			return new CommandeService($app);
		};
        $this['Rabbit'] = function ($app) {
            return new \T7FT\Services\RabbitManager($app, $app['rabbit.connection']);
        };
	}
}
