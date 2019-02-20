<?php

use SpeedBouffe\Database;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once 'lib/autoload.php';
require_once 'vendor/autoload.php';

$faker = Faker\Factory::create();
$db = new Database();

if (empty($argv[1]) == true) {
    $timer = 1000000;
} else {
    $timer = $argv[1]*1000;
}
//$timer = 1;
sleep(120);
$connection = new AMQPStreamConnection('RABBITMQ', 5672, 'guest', 'guest', '/');
$channel = $connection->channel();

$channel->queue_declare('commande', false, true, false, false);

//sleep(120);
while (true) {
    $result = array();
    usleep($timer);

    $firstAge = $db->getAge();
    $firstCivility = $db->getGender($firstAge, false);
    $firstNom = $faker->firstName;
    $firstPrenom = $faker->firstName;
    $firstEmail = $firstNom . '.' . $firstPrenom . '@' . $db->getSuffixEmail();
    $firstPersonPricing = $db->getPricing($firstAge);
    $firstPersonMeal = $db->getMeal();
    $firstPersonEntry = $db->getEntry();
    $firstPersonDessert = $db->getDessert();

    $nbMeal = $db->getNbMeal();

    $result['Acheteur']['Civilite'] = $firstCivility;
    $result['Acheteur']['Nom'] = $firstNom;
    $result['Acheteur']['Prenom'] = $firstPrenom;
    $result['Acheteur']['Age'] = $firstAge;
    $result['Acheteur']['Email'] = strtolower($firstEmail);

    $result['Infos_commande']['Jour'] = $db->getBuyDate();
    $result['Infos_commande']['Horaire_livraison'] = $db->getHour();
    $result['Infos_commande']['Paiement_espece'] = $db->needCash();


    for ($i = 0; $i < $nbMeal; $i++) {
	$cmd = "Commande" . $i;
	if ($i == 0) {
	$result['Details_commande'][$i][$cmd]['Repas'] = $firstPersonMeal;
	$result['Details_commande'][$i][$cmd]['Entree'] = $firstPersonEntry;
	$result['Details_commande'][$i][$cmd]['Dessert'] = $firstPersonDessert;
	    $result['Details_commande'][$i][$cmd]['Civilite'] = $firstCivility;
	    $result['Details_commande'][$i][$cmd]['Nom'] = $firstNom;
	    $result['Details_commande'][$i][$cmd]['Prenom'] = $firstPrenom;
	    $result['Details_commande'][$i][$cmd]['Age'] = $firstAge;
	    $result['Details_commande'][$i][$cmd]['Tarif'] = $firstPersonPricing;
	} else {
	    $otherAge = $db->getAge();
	    $otherCivility = $db->getGender($otherAge, false);
	    $otherNom = $faker->firstName;
	    $otherPrenom = $faker->firstName;
	    $otherPersonPricing = $db->getPricing($otherAge);
	    $otherPersonMeal = $db->getMeal();
	$otherPersonEntry = $db->getEntry();
	$otherPersonDessert = $db->getDessert();

	$result['Details_commande'][$i][$cmd]['Repas'] = $otherPersonMeal;
	$result['Details_commande'][$i][$cmd]['Entree'] = $otherPersonEntry;
	$result['Details_commande'][$i][$cmd]['Dessert'] = $otherPersonDessert;
	    $result['Details_commande'][$i][$cmd]['Civilite'] = $otherCivility;
	    $result['Details_commande'][$i][$cmd]['Nom'] = $otherNom;
	    $result['Details_commande'][$i][$cmd]['Prenom'] = $otherPrenom;
	    $result['Details_commande'][$i][$cmd]['Age'] = $otherAge;
	    $result['Details_commande'][$i][$cmd]['Tarif'] = $otherPersonPricing;
	}
    }

    $json = json_encode($result);

//    $ch = curl_init('http://HAPROXY/commande/new');
//    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//	    'Content-Type: application/json',
//	    'Content-Length: ' . strlen($json))
//    );
//    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
//    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
//
//    //execute post
//    $response = curl_exec($ch);
//
//    //close connection
//    curl_close($ch);

    $props = array('content_type' => 'application/json');
    $msg = new AMQPMessage($json, $props);
    $channel->basic_publish($msg, '', 'commande');

    //echo $json."\n";

    echo(json_encode($result));
    echo PHP_EOL;
}

$channel->close();
$connection->close();
