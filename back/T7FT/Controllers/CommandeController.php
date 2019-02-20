<?php

namespace T7FT\Controllers;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

use T7FT\Entity\Commande;
use T7FT\Entity\CommandeClient;
use T7FT\Entity\Client;
use T7FT\Entity\Repas;

use Symfony\Component\HttpFoundation\Request;

use T7FT\Controllers;


class CommandeController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/test/new/commande', [$this, 'testNewCommande']);
        $controllers->post('/commande/new', [$this, 'newCommande']);
        $controllers->get('/types_paiement', [$this, 'getTypesPaiement']);
        $controllers->get('/types_tarif', [$this, 'getTypesTarif']);
        $controllers->get('/commandes', [$this, 'getCommandesDuJour']);
        $controllers->get('/commande/{id}', [$this, 'getInfoCommande']);
        $controllers->get('/commandes/stats', [$this, 'getStatsCommande']);
        $controllers->get('/commandes/stats/paiement', [$this, 'getStatsCommandePaiement']);
        $controllers->get('/commandes/stats/jour', [$this, 'getStatsCommandeJour']);
        $controllers->get('/commandes/stats/horaire', [$this, 'getStatsCommandeHoraire']);
        $controllers->get('/commandes/stats/tarif', [$this, 'getStatsCommandeTarif']);

        $app['cors-enabled']($controllers, ['allowOrigin' => '*']);

        return $controllers;
    }

    /**
     * /test/new/commande
     * Route de test pour la création de commande
     */
    public function testNewCommande(Application $app, Request $req) {
        //Acheteur
        $client = $app['ClientService']->createClient('Achéteur', 'Tonio', 'Monsieur', 23, 'tonio@gmail.com');
        if (empty($client))
            return $app->json("Echec de l'enregistrement", 400);

        //Création de la commande
        $commande = $app['CommandeService']->createCommande(new \DateTime(), new \DateTime(), "Espece", $client);
        if (empty($commande))
            return $app->json("Echec de l'enregistrement", 400);

        //Premier mangeur
        $client = $app['ClientService']->createClient('Mangeur', 'BX', 'Monsieur', 23, 'bx@gmail.com');
        if (empty($client))
            return $app->json("Echec de l'enregistrement", 400);

        //Premier repas
        $repas = $app['RepasService']->createRepas("Steack Frites", "Salade", "Ile flotante");
        if (empty($repas))
            return $app->json("Echec de l'enregistrement", 400);

        //Ligne de commande
        $commandeClient = $app['CommandeService']->createLigneCommande($commande, $client, $repas, "Plein tarif");
        if (empty($commandeClient))
            return $app->json("Echec de l'enregistrement", 400);


        return $app->json("L'enregistrement de la commande a été effectuée", 200);
    }

    /**
     * POST commande/new
     * Création d'une nouvelle commande
     * Param :
     * {"Acheteur": {"Civilite":"Madame","Nom":"Houston","Prenom":"Clara","Age":55,"Email":"houston.clara@los-pollos-hermanos.com"},
     * "Infos_commande":{"Jour":"2017-11-02","Horaire_livraison":"18:30","Paiement_espece":"Non"},
     * "Details_commande":[{"Commande0":{"Repas":"Soupe nature","Entree":"Tomates Mozza","Dessert":"Cr\u00e8me brul\u00e9e","Civilite":"Madame","Nom":"Houston","Prenom":"Clara","Age":55,"Tarif":"Senior"}}]}
     */
    public function newCommande(Application $app, Request $req) {
        $em = $app['entityManager'];

        //Acheteur
        $acheteur = $req->get("Acheteur");
        $client = $app['ClientService']->createClient($acheteur['Nom'], $acheteur['Prenom'], $acheteur['Civilite'], $acheteur['Age'], $acheteur['Email']);
        if (empty($client))
            return $app->json("Echec de l'enregistrement", 400);

        //Création de la commande
        $infos = $req->get("Infos_commande");
        $commande = $app['CommandeService']->createCommande(new \DateTime($infos["Jour"]), new \DateTime($infos["Horaire_livraison"]), $infos["Paiement_espece"] == "Non" ? "Carte" : "Espece", $client);
        if (empty($commande))
            return $app->json("Echec de l'enregistrement", 400);
        //Parcours des différents clients à livrer
        $i = 0;
        foreach($req->get("Details_commande") as $details) {
            $details_commande = $details["Commande".$i];

            //Si première commande, le client est forcement l'acheteur, Sinon création du client
            if ($i != 0) {
                $client = $app['ClientService']->createClient($details_commande['Nom'], $details_commande['Prenom'], $details_commande['Civilite'], $details_commande['Age']);
                if (empty($client))
                    return $app->json("Echec de l'enregistrement", 400);
            }

            //Enregistrement du repas
            $repas = $app['RepasService']->createRepas($details_commande['Repas']);
            if (empty($repas))
                return $app->json("Echec de l'enregistrement", 400);

            //Enregistrement de l'entrée
            $entree = $app['RepasService']->createEntree($details_commande['Entree']);
            if (empty($entree))
                return $app->json("Echec de l'enregistrement", 400);

            //Enregistrement du dessert
            $dessert = $app['RepasService']->createDessert($details_commande['Dessert']);
            if (empty($dessert))
                return $app->json("Echec de l'enregistrement", 400);

            //Enregistrement des details de la commande
            //Ligne de commande
            $commandeClient = $app['CommandeService']->createLigneCommande($commande, $client, $repas, $details_commande['Tarif'], $entree, $dessert);

            if (empty($commandeClient))
                return $app->json("Echec de l'enregistrement", 400);

            $i++;
        }

        return $app->json("L'enregistrement de la commande a été effectuée", 200);
    }

    /**
     * /types_paiement
     * retourne les types de paiement
     */
    function getTypesPaiement(Application $app, Request $req)
    {
        $types = array('Espece', 'Carte', 'Ticket Restaurant');

        return $app->json($types, 200);
    }

    /**
     * /types_tarif
     * retourne les types de tarif
     */
    function getTypesTarif(Application $app, Request $req)
    {
        $types = array('Tarif etudiant','Senior','Plein tarif','Tarif ami');

        return $app->json($types, 200);
    }

    /**
     * /commandes
     * Retourne les commandes du jour
     */
    function getCommandesDuJour(Application $app, Request $req)
    {
        $commandes = $app['Commande']->findCommandesDuJour();

        return $app->json($commandes, 200);
    }

    /**
     * /commande/{id}
     * Retourne toutes les infos d'une commande
     * $id de la commande concernée
     */
    function getInfoCommande(Application $app, $id)
    {
        $commande = $app['Commande']->findInfoCommande($id);

        return $app->json($commande, 200);
    }

    /**
     * /commandes/stats
     * Retourne les stats globales des commandes
     */
    function getStatsCommande(Application $app)
    {
        $stats = $app['Commande']->findStatsCommande();

        return $app->json($stats, 200);
    }

    /**
     * /commandes/stats/paiement
     * Retourne les stats des commandes en fonction du type de paiement
     */
    function getStatsCommandePaiement(Application $app)
    {
        $stats = $app['Commande']->findStatsCommandePaiement();

        return $app->json($stats, 200);
    }

    /**
     * /commandes/stats/jour
     * Retourne les stats des commandes en fonction des jours
     */
    function getStatsCommandeJour(Application $app)
    {
        $stats = $app['Commande']->findStatsCommandeJour();

        return $app->json($stats, 200);
    }

    /**
     * /commandes/stats/horaire
     * Retourne les stats des commandes en fonction de l'horaire
     */
    function getStatsCommandeHoraire(Application $app)
    {
        $stats = $app['Commande']->findStatsCommandeHoraire();

        return $app->json($stats, 200);
    }

    /**
     * /commandes/stats/tarif
     * Retourne les stats des commandes en fonction du type de tarif
     */
    function getStatsCommandeTarif(Application $app)
    {
        $stats = $app['Commande']->findStatsCommandeTarif();

        return $app->json($stats, 200);
    }



}