<?php

include_once __DIR__ . "/../vendor/autoload.php";

define('KOALA_BATCH_VERSION_NUMBER', '0.0.1');

$app = new \Koalamon\KoalaBatch\Cli\Application();
$app->run();
