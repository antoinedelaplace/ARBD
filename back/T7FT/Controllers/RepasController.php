<?php

namespace T7FT\Controllers;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\Request;

use T7FT\Controllers;

class RepasController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/test/new/repas', [$this, 'testNewRepas']);
        $controllers->post('repas/new', [$this, 'newRepas']);
        $controllers->get('/repas', [$this, 'getRepas']);
        $controllers->get('/repas/classement', [$this, 'getEntreeDessertParRepasStats']);
        $controllers->get('/repas/stats', [$this, 'getRepasStats']);

        $app['cors-enabled']($controllers, ['allowOrigin' => '*']);

        return $controllers;
    }

    /**
     * /test/new/repas
     * Route de test pour la création de repas
     */
    public function testNewRepas(Application $app, Request $req) {
        $repas = $app['RepasService']->createRepas("Couscous");
        if ($repas)
            return $app->json("Le repas a bien été enregistré", 200);
        else
            return $app->json("Echec de l'enregistrement", 400);
    }

    /**
     * POST repas/new
     * Création d'un nouveau repas
     * Param :
     * titre du nouveau repas
     */
    public function newRepas(Application $app, Request $req) {
        $repas = $app['RepasService']->createRepas($req->get("titre"));

        if ($repas)
            return $app->json("Le repas a bien été enregistré", 200);
        else
            return $app->json("Echec de l'enregistrement", 400);
    }

    /**
     * /repas
     * Retourne l'ensemble des repas
     */
    public function getRepas(Application $app, Request $req) {
        $listRepas = $app['Repas']->findAllRepas();

        if ($listRepas)
            return $app->json($listRepas, 200);
        else {
            return $app->json($listRepas, 400);
        }
    }

    /**
     * /repas/stats
     * Retourne les stats sur les repas
     */
    public function getRepasStats(Application $app, Request $req) {
        $stats = $app['Repas']->findRepasCommandeTotal();

        if ($stats)
            return $app->json($stats, 200);
        else {
            return $app->json($stats, 400);
        }
    }

    /**
     * /repas/classement
     * Retourne les stats sur les entrées et desserts commandés par repas
     */
    public function getEntreeDessertParRepasStats(Application $app, Request $req) {
        $stats = $app['Repas']->findEntreeDessertParRepas();

        if ($stats)
            return $app->json($stats, 200);
        else {
            return $app->json($stats, 400);
        }
    }
}
