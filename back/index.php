<?php
error_reporting(-1);
ini_set('display_errors', 'On');

require_once "bootstrap.php";

$app['debug'] = true;

$app->run();

?>
