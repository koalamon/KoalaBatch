<?php

include_once __DIR__ . "/../vendor/autoload.php";

define('KOALA_BATCH_VERSION_NUMBER', '##development##');

$app = new \Koalamon\KoalaBatch\Cli\Application();
$app->run();
