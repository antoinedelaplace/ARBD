<?php

namespace T7FT\Controllers;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\Request;

use T7FT\Controllers;

class EntreeController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/test/new/entree', [$this, 'testNewEntree']);
        $controllers->post('entree/new', [$this, 'newEntree']);
        $controllers->get('/entrees', [$this, 'getEntrees']);
        $controllers->get('/entrees/stats', [$this, 'getEntreesStats']);

        $app['cors-enabled']($controllers, ['allowOrigin' => '*']);

        return $controllers;
    }

    /**
     * /test/new/entree
     * Route de test pour la création d'entrée
     */
    public function testNewEntree(Application $app, Request $req) {
        $repas = $app['RepasService']->createEntree("Salade");
        if ($repas)
            return $app->json("L'entrée a bien été enregistré", 200);
        else
            return $app->json("Echec de l'enregistrement", 400);
    }

    /**
     * POST entree/new
     * Création d'une nouvelle entrée
     * Param :
     * titre de la nouvelle entrée
     */
    public function newEntree(Application $app, Request $req) {
        $repas = $app['RepasService']->createEntree($req->get("titre"));

        if ($repas)
            return $app->json("L'entrée a bien été enregistré", 200);
        else
            return $app->json("Echec de l'enregistrement", 400);
    }

    /**
     * /entrees
     * Retourne l'ensemble des entrees
     */
    public function getEntrees(Application $app, Request $req) {
        $listeEntrees = $app['Entree']->findAllEntrees();

        if ($listeEntrees)
            return $app->json($listeEntrees, 200);
        else {
            return $app->json($listeEntrees, 400);
        }
    }

    /**
     * /entrees/stats
     * Retourne les stats sur les entrées
     */
    public function getEntreesStats(Application $app, Request $req) {
        $stats = $app['Entree']->findEntreesCommandeTotal();

        if ($stats)
            return $app->json($stats, 200);
        else {
            return $app->json($stats, 400);
        }
    }
}
