<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Goutte\Client;

class SpeciesCommand extends Command
{
    protected static $defaultName = 'scrape-species';
    protected $url = "https://www.maldivesbiodiversity.org";

    protected function configure()
    {
        $this->setDescription('Scrapes species');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new Client();

        $crawler = $client->request('GET', $this->url . "/Species");

        $links = [];

        $crawler->filter('.c-overlay-content > a')->each(function ($node) use (&$links) {
            $links[] = $node->attr('href');
        });

        foreach ($links as $link) {
            $crawler = $client->request('GET', $this->url . $link);

            $data[] = [
                "title" => $crawler->filter('.c-content-title-2 h4')->first()->text(),
                "short-desc" => $crawler->filter('.c-product-short-desc')->first()->text(),
                "dhivehi_name" => $crawler->filter('.DhivehiText')->first()->text(),
                "image" => $crawler->filter('img')->first()->attr('src')
            ];
        }

        $fp = fopen('data/species.json', 'w+');
        fwrite($fp, json_encode($data));
        fclose($fp);

        return Command::SUCCESS;
    }
}
