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
    protected function executeProjects(array $projects, $command, OutputInterface $output, Client $client, $withSubsystems = false)
    {
        foreach ($projects as $project) {
            $systems = $client->getSystems($project);

            foreach ($systems as $mainSystem) {

                if ($withSubsystems) {
                    $systemsWithSubSystems = $mainSystem->getSubSystems();
                } else {
                    $systemsWithSubSystems = array();
                }

                $systemsWithSubSystems[] = $mainSystem;

                $this->executeSystems($systemsWithSubSystems, $command, $output);
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
        foreach ($systems as $systemConfig) {
            $system = $systemConfig['system'];

            $translation = array(
                'system_name' => $system->getName(),
                'system_identifier' => $system->getIdentifier(),
                'system_url' => $system->getUrl(),
                'project_identifier' => $system->getProject()->getIdentifier(),
                'project_api_key' => $system->getProject()->getApiKey(),
                'project_name' => $system->getProject()->getName(),
                'options' => json_encode($systemConfig['options'])
            );

            $translatedCommand = $this->translate($translation, $command);

            $this->executeCommand($output, $translatedCommand);
        }
    }

    protected function executeCommand(OutputInterface $output, $command)
    {
        $outputString = '';

        $output->writeln("  Executing: " . $command);
        exec($command, $outputString, $return_var);

        $output->writeln("  Output:");
        foreach ($outputString as $outputElement) {
            $output->writeln("   " . $outputElement);
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
