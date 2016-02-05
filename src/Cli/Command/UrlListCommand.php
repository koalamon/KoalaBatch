<?php

namespace Koalamon\KoalaBatch\Cli\Command;

use GuzzleHttp\Client;
use Koalamon\Client\Entity\Project;
use Symfony\Component\Console\Input\InputArgument;
use Koalamon\Client\Client as KoalaClient;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UrlListCommand extends BatchCommand
{
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('url', InputArgument::REQUIRED, 'the url that will return a json encoded list auf systems'),
                new InputArgument('exec', InputArgument::REQUIRED, 'the command to be executed'),
            ))
            ->setDescription('run batch commands for all systems of a given url')
            ->setName('urlList');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $httpClient = new Client();

        $client = new KoalaClient($httpClient);

        $urls = $client->getUrlsFromUrl($input->getArgument('url'));

        foreach ($urls as $url) {
            $this->executeCommand($output, str_replace('#url#', $url, $input->getArgument('exec')));
        }
    }
}