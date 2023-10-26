<?php

namespace App\Service\FeedReader;

use Symfony\Component\DomCrawler\Crawler;

class FeedReader
{

    public function getNewest(string $feedUri, string $platform): News
    {
        $crawler = $this->readXml($feedUri);

        return new News(
            $crawler->filter('item > link')->first()->text(),
            $crawler->filter('item > title')->first()->text(),
            strip_tags($crawler->filter('item > description')->first()->text()),
            $platform
        );
    }

    private function readXml(string $feedUri): Crawler
    {
        $crawler = new Crawler();

        $xml = file_get_contents($feedUri);
        $crawler->addXmlContent($xml);

        return $crawler;
    }

}