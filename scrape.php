<?php

require __DIR__ . '/vendor/autoload.php';
use Symfony\Component\Console\Application;
use App\Commands\SpeciesCommand;

$app = new Application();

$app->add(new SpeciesCommand());

$app->run();