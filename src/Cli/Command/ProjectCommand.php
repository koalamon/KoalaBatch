<?php

namespace Koalamon\KoalaBatch\Cli\Command;

use GuzzleHttp\Client;
use Koalamon\Client\Entity\Project;
use Symfony\Component\Console\Input\InputArgument;
use Koalamon\Client\Client as KoalaClient;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ProjectCommand extends BatchCommand
{
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('project_identifier', InputArgument::REQUIRED, 'the user name'),
                new InputArgument('api_key', InputArgument::REQUIRED, 'the project api key'),
                new InputArgument('exec', InputArgument::REQUIRED, 'the command to be executed'),
                new InputOption('koalamon_server', 'k', InputOption::VALUE_OPTIONAL, 'the command to be executed'),
                new InputOption('with-subsystems', 's', InputOption::VALUE_NONE, 'run the command for all subsystems'),
            ))
            ->setDescription('run batch commands for all systems of a given project')
            ->setName('project');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $httpClient = new Client();

        if ($input->getOption('koalamon_server')) {
            $client = new KoalaClient($httpClient, $input->getOption('koalamon_server'));
        } else {
            $client = new KoalaClient($httpClient);
        }

        $projects = [new Project('', $input->getArgument('project_identifier'), $input->getArgument('api_key'))];

        if ($input->getOption('with-subsystems')) {
            $withSubsystems = true;
        } else {
            $withSubsystems = false;
        }

        var_dump($withSubsystems);

        $this->executeProjects($projects, $input->getArgument('exec'), $output, $client, $withSubsystems);
    }
}