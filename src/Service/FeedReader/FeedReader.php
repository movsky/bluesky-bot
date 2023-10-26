<?php

namespace App\Service\FeedReader;

use Symfony\Component\DomCrawler\Crawler;

class FeedReader
{

    public function getNews(string $feedUri, string $platform): array
    {
        $crawler = $this->readXml($feedUri);

        return $crawler->filter('item')->each(function (Crawler $node, string $platform): News {
            return new News(
                $node->filter('link')->text(),
                $node->filter('title')->text(),
                $node->filter('description')->text(),
                $platform
            );
        });
    }

    public function getNewest(string $feedUri, string $platform): News
    {
        $crawler = $this->readXml($feedUri);

        return new News(
            $crawler->filter('item > link')->first()->text(),
            $crawler->filter('item > title')->first()->text(),
            $crawler->filter('item > description')->first()->text(),
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