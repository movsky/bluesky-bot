<?php

namespace App\Service\CaptionProvider;

class CaptionProvider
{

    private array $captions = [
        'wir leben in einer simulation.',
        'wir leben in einer gesellschaft.',
        'was macht das mit euch?',
        'niemand:
        
absolut niemand:
        
nachrichten:',
        'cool und normal'
    ];

    public function getRandomCaption(): string
    {
        shuffle($this->captions);

        return current($this->captions);
    }

}