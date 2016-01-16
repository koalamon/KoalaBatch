<?php

include_once __DIR__ . "/../vendor/autoload.php";

define('KOALA_BATCH_VERSION_NUMBER', '0.2.0');

$app = new \Koalamon\KoalaBatch\Cli\Application();
$app->run();
