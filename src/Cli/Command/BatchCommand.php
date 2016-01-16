<?php

namespace Koalamon\KoalaBatch\Cli\Command;

use Koalamon\Client\Client;
use Koalamon\Client\Entity\project;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

class BatchCommand extends Command
{
    /**
     * @param Project[] $projects
     * @param string $command
     * @param array $translation
     * @param OutputInterface $output
     */
    protected function executeProjects(array $projects, $command, OutputInterface $output, Client $client)
    {
        foreach ($projects as $project) {
            $systems = $client->getSystems($project);

            $translation = array(
                'project_identifier' => $project->getIdentifier(),
                'project_api_key' => $project->getApiKey(),
                'project_name' => $project->getName()
            );

            foreach ($systems as $mainSystem) {

                $withSubSystems = $mainSystem->getSubSystems();
                $withSubSystems[] = $mainSystem;

                $this->executeSystems($withSubSystems, $command, $output);
            }
        }
    }

    /**
     * @param System[] $systems
     * @param $command
     * @param OutputInterface $outputInterface
     * @param Client $client
     */
    protected function executeSystems(array $systems, $command, OutputInterface $output)
    {
        foreach ($systems as $system) {

            $translation = array(
                'system_name' => $system->getName(),
                'system_identifier' => $system->getIdentifier(),
                'system_url' => $system->getUrl(),
                'project_identifier' => $system->getProject()->getIdentifier(),
                'project_api_key' => $system->getProject()->getApiKey(),
                'project_name' => $system->getProject()->getName()
            );

            $translatedCommand = $this->translate($translation, $command);

            $outputString = '';

            $output->writeln("  Executing: " . $translatedCommand);
            exec($translatedCommand, $outputString, $return_var);

            $output->writeln("  Output:");
            foreach ($outputString as $outputElement) {
                $output->writeln("   " . $outputElement);
            }
        }
    }

    private function translate($translationArray, $text)
    {
        foreach ($translationArray as $key => $value) {
            $text = str_replace('#' . $key . '#', $value, $text);
        }
        return $text;
    }
}
