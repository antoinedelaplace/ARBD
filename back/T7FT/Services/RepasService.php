<?php

namespace T7FT\Services;

use Silex\Application;
use T7FT\Entity\Repas;
use T7FT\Entity\Entree;
use T7FT\Entity\Dessert;

class RepasService
{
    private $app;

    function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Création d'un nouveau repas dans la base
     * Pas de création si déjà existant
     * @param $titre du nouveau repas
     * @return null|Repas créé
     */
    public function createRepas($titre)
    {
        $repas = $this->app['Repas']->findOneByTitre($titre);

        if (empty($repas)) {
            try {
                $em = $this->app['entityManager'];
                $repas = new Repas();
                $repas->setTitre($titre);
                $em->persist($repas);
                $em->flush();
            } catch (\Exception $e) {
                return NULL;
            }
        }
        return $repas;
    }

    /**
     * Création d'une nouvelle entrée dans la base
     * Pas de création si déjà existant
     * @param $titre de la nouvelle entrée
     * @return null|Entree créé
     */
    public function createEntree($titre)
    {
        $entree = $this->app['Entree']->findOneByTitre($titre);

        if (empty($entree)) {
            try {
                $em = $this->app['entityManager'];
                $entree = new Entree();
                $entree->setTitre($titre);
                $em->persist($entree);
                $em->flush();
            } catch (\Exception $e) {
                return NULL;
            }
        }
        return $entree;
    }

    /**
     * Création d'un nouveau dessert dans la base
     * Pas de création si déjà existant
     * @param $titre du nouveau dessert
     * @return null|Dessert créé
     */
    public function createDessert($titre)
    {
        $dessert = $this->app['Dessert']->findOneByTitre($titre);

        if (empty($dessert)) {
            try {
                $em = $this->app['entityManager'];
                $dessert = new Dessert();
                $dessert->setTitre($titre);
                $em->persist($dessert);
                $em->flush();
            } catch (\Exception $e) {
                return NULL;
            }
        }
        return $dessert;
    }
}