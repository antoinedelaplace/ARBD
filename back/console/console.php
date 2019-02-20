<?php

set_time_limit(0);

require_once __DIR__.'/../vendor/autoload.php';

$app = new \T7FT\Application();


$console = &$app['console'];
$commands_tab = [
    "ConsumeCommande"
];

foreach ($commands_tab as $cmd) {
    $class_name = "\\T7FT\\Command\\" . $cmd;
    if (class_exists($class_name)
        && in_array("Knp\Command\Command", class_parents($class_name))) {
        $console->add(new $class_name());
    }
}

$console->run();
