<?php

namespace T7FT\Command;

use Knp\Command\Command as BaseCommand;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PhpAmqpLib\Message\AMQPMessage;


class ConsumeCommande extends BaseCommand
{
    protected function configure()
    {
        parent::configure();
        $this
            ->setName("consumecommande")
            ->setDescription("Infite consumer for league");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $app = $this->getSilexApplication();
        // DO NOT DELETE THIS OR I KILL YOU
        $app['swiftmailer.use_spool'] = false;
        $app->flush();

        $app['Rabbit']->prepareForConsumer(
            't7ft',
            'commande',
            't7ft',
            'error',
            'commande_error',
            'error'
        );

        $callback = function (AMQPMessage $msg) use ($app) {
            try {
                if ($this->newCommande($msg->body) == false)
                    $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag']);
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
            } catch (\Exception $e) {
                $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag']);
            }
        };

        $app['Rabbit']->infiniteConsume('commande', $callback);
    }

    private function newCommande($json) {
        $app = $this->getSilexApplication();
        $em = $app['entityManager'];
        $json = json_decode($json, true); //var_dump(json_decode($json));
        $acheteur = $json["Acheteur"];
        $client = $app['ClientService']->createClient($acheteur['Nom'], $acheteur['Prenom'], $acheteur['Civilite'], $acheteur['Age'], $acheteur['Email']);
        if (empty($client))
            return false;
        $infos = $json["Infos_commande"];
        $commande = $app['CommandeService']->createCommande(new \DateTime($infos["Jour"]), new \DateTime($infos["Horaire_livraison"]), $infos["Paiement_espece"] == "Non" ? "Carte" : "Espece", $client);
        if (empty($commande))
            return false;
       // $em->flush();
        //Parcours des différents clients à livrer
        $i = 0;
        foreach($json["Details_commande"] as $details) {
            $details_commande = $details["Commande".$i];

            //Si première commande, le client est forcement l'acheteur, Sinon création du client
            if ($i != 0) {
                $client = $app['ClientService']->createClient($details_commande['Nom'], $details_commande['Prenom'], $details_commande['Civilite'], $details_commande['Age']);
                if (empty($client))
                    return false;
                //$em->flush();
            }

            //Enregistrement du repas
            $repas = $app['RepasService']->createRepas($details_commande['Repas']);
            if (empty($repas))
                return false;

            //Enregistrement de l'entrée
            $entree = $app['RepasService']->createEntree($details_commande['Entree']);
            if (empty($entree))
                return false;

            //Enregistrement du dessert
            $dessert = $app['RepasService']->createDessert($details_commande['Dessert']);
            if (empty($dessert))
                return false;

            //Enregistrement des details de la commande
            //Ligne de commande
            $commandeClient = $app['CommandeService']->createLigneCommande($commande, $client, $repas, $details_commande['Tarif'], $entree, $dessert);

            if (empty($commandeClient))
                return false;
          //  $em->flush();
            $i++;
        }

        $em->clear();
        return true;

    }
}
