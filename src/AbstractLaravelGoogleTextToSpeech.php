<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech;

use MoeMizrak\LaravelGoogleTextToSpeech\Adapters\TextToSpeechClientInterface;
use MoeMizrak\LaravelGoogleTextToSpeech\Helpers\TextToSpeechRequestHelper;

abstract readonly class AbstractLaravelGoogleTextToSpeech
{
    public function __construct(
        protected TextToSpeechClientInterface $client,
        protected TextToSpeechRequestHelper $requestHelper,
    ) {}
}
