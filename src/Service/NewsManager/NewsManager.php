<?php

namespace App\Service\NewsManager;

use App\Service\FeedLogger\FeedLogger;
use App\Service\FeedReader\FeedReader;
use App\Service\FeedReader\News;

class NewsManager
{

    private array $feeds = [
        'tagesschau' => 'https://www.tagesschau.de/index~rss2.xml',
        'zdf' => 'https://www.zdf.de/rss/zdf/nachrichten',
        'sueddeutsche' => 'https://rss.sueddeutsche.de/rss/Topthemen',
        'tagesspiegel' => 'https://www.tagesspiegel.de/contentexport/feed/home',
        'spon' => 'https://www.spiegel.de/schlagzeilen/index.rss',
        'taz' => 'https://taz.de/!p4608;rss/',
    ];

    public function __construct(
        private readonly FeedReader $feedReader,
        private readonly FeedLogger $feedLogger
    )
    {
        shuffle($this->feeds);
    }

    public function getNews(): ?News
    {
        if (empty($this->feeds)) {
            return null;
        }

        $news = $this->feedReader->getNewest(current($this->feeds), key($this->feeds));
        $log = $this->feedLogger->getLogAsArray();

        if (in_array($news->getId(), $log)) {
            $this->removeCurrentFeed();

            return $this->getNews();
        }

        $this->feedLogger->log($news->getId());

        return $news;
    }

    private function removeCurrentFeed(): void
    {
        unset($this->feeds[0]);
        $this->feeds = array_values($this->feeds);
    }

}