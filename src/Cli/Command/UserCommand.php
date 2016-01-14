<?php

namespace Koalamon\KoalaBatch\Cli\Command;

use GuzzleHttp\Client;
use Koalamon\Client\Entity\User;
use Symfony\Component\Console\Input\InputArgument;
use Koalamon\Client\Client as KoalaClient;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UserCommand extends BatchCommand
{
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('username', InputArgument::REQUIRED, 'the user name'),
                new InputArgument('api_key', InputArgument::REQUIRED, 'the user api key'),
                new InputArgument('exec', InputArgument::REQUIRED, 'the command to be executed'),
                new InputOption('koalamon_server', 'k', InputOption::VALUE_OPTIONAL, 'the command to be executed'),
            ))
            ->setDescription('run batch commands for all systems of a given user')
            ->setName('user');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $httpClient = new Client();

        if($input->getOption('koalamon_server')) {
            $client = new KoalaClient($httpClient, $input->getOption('koalamon_server'));
        }else {
            $client = new KoalaClient($httpClient);
        }

        $projects = $client->getProjects(new User($input->getArgument('username'), $input->getArgument('api_key')));

        $this->executeProjects($projects, $input->getArgument('exec'), $output, $client);
    }
}