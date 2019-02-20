<?php

namespace T7FT\Services;

use Silex\Application;
use T7FT\Entity\Commande;
use T7FT\Entity\CommandeClient;
use T7FT\Entity\Entree;

class CommandeService
{
    private $app;

    function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Création d'une nouvelle commande dans la base
     * L'état de la commande passe à 'En attente' et le paiement est à 0
     * @param $dateLivraison
     * @param $horaireLivraison
     * @param $typePaiement
     * @param $acheteur Client qui passe la commande
     * @return null|Commande créée
     */
    public function createCommande($dateLivraison, $horaireLivraison, $typePaiement, $acheteur)
    {
        try {
            $em = $this->app['entityManager'];
            $commande = new Commande();
            $commande->setDateCreation(new \DateTime());
            $commande->setDateLivraison($dateLivraison);
            $commande->setHoraireLivraison($horaireLivraison);
            $commande->setTypePaiement($typePaiement);
            $commande->setEtat("En attente");
            $commande->setPaiement(0);
            $commande->setAcheteur($acheteur);
            $em->persist($commande);
            $em->flush();
        }
        catch (\Exception $e){
            return NULL;
        }
        return $commande;
    }

    /**
     * Création d'une ligne de commande pour chaque personne à livrer
     * @param $commande Commande à laquelle est rattachée la ligne de commande
     * @param $client Client à livrer
     * @param $repas Repas choisi par le client
     * @param $tarif du client
     * @param $entree Entree choisie par le client
     * @param $dessert Dessert choisi par le client
     * @return null|CommandeClient créée
     */
    public function createLigneCommande($commande, $client, $repas, $tarif, $entree, $dessert)
    {
        try {
            $entity_tarif = $this->app['Tarif']->findOneByTitre($tarif);
            $em = $this->app['entityManager'];
            $commandeClient = new CommandeClient();
            $commandeClient->setCommande($commande);
            $commandeClient->setClient($client);
            $commandeClient->setRepas($repas);
            $commandeClient->setEntree($entree);
            $commandeClient->setDessert($dessert);
            $commandeClient->setTarif($entity_tarif);
            $em->persist($commandeClient);
            $em->flush();
        }
        catch (\Exception $e){
            return NULL;
        }
        return $commandeClient;
    }
}