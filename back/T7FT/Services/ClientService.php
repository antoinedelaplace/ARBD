<?php

namespace T7FT\Services;

use Silex\Application;
use T7FT\Entity\Client;

class ClientService
{
    private $app;

    function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Création d'un nouveau client dans la base
     * Pas de création si déjà existant
     * @param $nom
     * @param $prenom
     * @param $civilite
     * @param $age
     * @param string $email
     * @return null|Client créé
     */
    public function createClient($nom, $prenom, $civilite, $age, $email="")
    {
        $client = $this->app['Client']->findOneBy(array('nom' => $nom, 'prenom' => $prenom, 'civilite' => $civilite, 'age' => $age));
        if (empty($client)) {
            try {
                $em = $this->app['entityManager'];
                $client = new Client();
                $client->setNom($nom);
                $client->setPrenom($prenom);
                $client->setAge($age);
                $client->setCivilite($civilite);
                if ($email != "")
                    $client->setEmail($email);
                $em->persist($client);
                $em->flush();
            } catch (\Exception $e) {
                return NULL;
            }
        }
        return $client;
    }
}