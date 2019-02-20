<?php

namespace T7FT\Controllers;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

use T7FT\Entity\Client;

use Symfony\Component\HttpFoundation\Request;

use T7FT\Controllers;

class ClientController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/test/new/client', [$this, 'testNewClient']);
        $controllers->post('/client/new', [$this, 'newClient']);
        $controllers->get('/clients', [$this, 'getClients']);
        $controllers->get('/client/{id}', [$this, 'getInfoClient']);
        $controllers->get('/clients/stats/civilite', [$this, 'getInfoCommandeCivilite']);
        $controllers->get('/clients/stats/age', [$this, 'getInfoCommandeAge']);
        $controllers->get('/clients/stats/email', [$this, 'getInfoCommandeEmail']);

        $app['cors-enabled']($controllers, ['allowOrigin' => '*']);

        return $controllers;
    }

    /**
     * /test/new/client
     * Route de test pour la création de client
     */
    public function testNewClient(Application $app, Request $req) {
        $client = $app['ClientService']->createClient('HOUVET', 'Benoit-Xavier', "Mademoiselle", 12, "benoitxavier@google.com");

        if ($client)
            return $app->json("Le client a bien été enregistré", 200);
        else
            return $app->json("Echec de l'enregistrement", 400);
    }

    /**
     * POST client/new
     * Création d'un nouveau client
     * Param :
     * nom
     * prenom
     * civilite
     * age
     * email
     */
    public function newClient(Application $app, Request $req) {
        $client = $app['ClientService']->createClient($req->get("nom"), $req->get("prenom"), $req->get("civilite"), $req->get("age"), $req->get("email"));

        if ($client)
            return $app->json("Le client a bien été enregistré", 200);
        else
            return $app->json("Echec de l'enregistrement", 400);
    }

    /**
     * /clients
     * Retourne l'ensemble des clients
     */
    public function getClients(Application $app, Request $req) {
        $listClients = $app['Client']->findAllClientName();

        return $app->json($listClients, 400);
    }

    /**
     * /client/{id}
     * Retourne toutes les infos d'un client
     * $id du client concerné
     */
    public function getInfoClient(Application $app, $id) {
        $client = $app['Client']->findClientInfo($id);

        return $app->json($client, 400);
    }

    /**
     * /clients/stats/civilite
     * retourne les différentes stats en fonction de la civilité du client
     */
    public function getInfoCommandeCivilite(Application $app) {
        $stats = $app['Client']->findInfoCommandeCivilite();

        return $app->json($stats, ($stats) ? 200 : 400);
    }

    /**
     * /clients/stats/age
     * retourne les différentes stats en fonction de l'age du client
     */
    public function getInfoCommandeAge(Application $app) {
        $stats = $app['Client']->findInfoCommandeAge();

        return $app->json($stats, ($stats) ? 200 : 400);
    }

    /**
     * /clients/stats/email
     * retourne les différentes stats en fonction de l'email du client
     */
    public function getInfoCommandeEmail(Application $app) {
        $stats = $app['Client']->findInfoCommandeEmail();

        return $app->json($stats, ($stats) ? 200 : 400);
    }

}
