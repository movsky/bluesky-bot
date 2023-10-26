<?php

namespace App\Command;

use App\Service\CaptionProvider\CaptionProvider;
use App\Service\FeedReader\News;
use App\Service\NewsManager\NewsManager;
use Mov\BlueskyApi\BlueskyApi;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(name: 'bluesky:post')]
class PostCommand extends Command
{

    private BlueskyApi $blueskyApi;

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function __construct(
        private readonly NewsManager $newsManager,
        private readonly CaptionProvider $captionProvider,
        string $user,
        string $password,
    ) {
       $this->blueskyApi = new BlueskyApi();
       $this->blueskyApi->authenticate($user, $password);

       parent::__construct();
    }

    /**
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $news = $this->newsManager->getNews();

        if (null === $news) {
            return Command::SUCCESS;
        }

        $card = $this->buildCard($news);

        $this->blueskyApi->post($this->captionProvider->getRandomCaption(), $card);

        return Command::SUCCESS;
    }

    private function buildCard(News $news): array
    {
        return [
            '$type' => 'app.bsky.embed.external',
            'external' => [
                'uri' => $news->getUri(),
                'title' => $news->getTitle(),
                'description' => $news->getDescription(),
            ],
        ];
    }

}