<?php

include_once __DIR__ . "/../vendor/autoload.php";

function translate($translationArray, $text)
{
    foreach ($translationArray as $key => $value) {
        $text = str_replace('#' . $key . '#', $value, $text);
    }

    return $text;
}

$username = $argv[1];
$userApiKey = $argv[2];
$command = $argv[3];

$httpClient = new \GuzzleHttp\Client();
$client = new \Koalamon\Client\Client($username, $userApiKey, $httpClient);

$projects = $client->getProjects();

foreach ($projects as $project) {
    $systems = $client->getSystems($project);
    $reporter = new \Koalamon\Client\Reporter\Reporter($project->getIdentifier(), $project->getApiKey(), $httpClient);

    $translation = array(
        'project_identifier' => $project->getIdentifier(),
        'project_api_key' => $project->getApiKey(),
        'project_name' => $project->getName()
    );

    foreach ($systems as $system) {

        $translation = array_merge($translation, array(
            'system_name' => $system->getName(),
            'system_identifier' => $system->getIdentifier(),
            'system_url' => $system->getUrl()
        ));

        $translatedCommand = translate($translation, $command);

        $output = '';
        exec($translatedCommand, $output, $return_var);

        echo implode("\n", $output) . "\n";
    }
}

echo "\n\n";