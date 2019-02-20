<?php

namespace T7FT\Controllers;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\Request;

use T7FT\Controllers;

class DessertController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/test/new/dessert', [$this, 'testNewDessert']);
        $controllers->post('dessert/new', [$this, 'newDessert']);
        $controllers->get('/desserts', [$this, 'getDesserts']);
        $controllers->get('/desserts/stats', [$this, 'getDessertsStats']);

        $app['cors-enabled']($controllers, ['allowOrigin' => '*']);

        return $controllers;
    }

    /**
     * /test/new/dessert
     * Route de test pour la création de dessert
     */
    public function testNewDessert(Application $app, Request $req) {
        $dessert = $app['RepasService']->createDessert("Gateau");
        if ($dessert)
            return $app->json("Le dessert a bien été enregistré", 200);
        else
            return $app->json("Echec de l'enregistrement", 400);
    }

    /**
     * POST dessert/new
     * Création d'un nouveau dessert
     * Param :
     * titre du nouveau dessert
     */
    public function newDessert(Application $app, Request $req) {
        $dessert = $app['RepasService']->createDessert($req->get("titre"));

        if ($dessert)
            return $app->json("Le dessert a bien été enregistré", 200);
        else
            return $app->json("Echec de l'enregistrement", 400);
    }

    /**
     * /desserts
     * Retourne l'ensemble des desserts
     */
    public function getDesserts(Application $app, Request $req) {
        $listeDesserts = $app['Dessert']->findAllDesserts();

        if ($listeDesserts)
            return $app->json($listeDesserts, 200);
        else {
            return $app->json($listeDesserts, 400);
        }
    }

    /**
     * /desserts/stats
     * Retourne les stats sur les desserts
     */
    public function getDessertsStats(Application $app, Request $req) {
        $stats = $app['Dessert']->findDessertsCommandeTotal();

        if ($stats)
            return $app->json($stats, 200);
        else {
            return $app->json($stats, 400);
        }
    }
}
