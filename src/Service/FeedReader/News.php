<?php

namespace App\Service\FeedReader;

class News
{

    public function __construct(
        private readonly string $uri,
        private readonly string $title,
        private readonly string $description,
        private readonly string $platform,
    ) {
    }

    public function getId(): string
    {
        return md5($this->uri . $this->title . $this->description);
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getPlatform(): string
    {
        return $this->platform;
    }

}