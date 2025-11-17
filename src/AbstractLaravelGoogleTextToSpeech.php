<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech;

use MoeMizrak\LaravelGoogleTextToSpeech\Adapters\AdapterInterface;

abstract readonly class AbstractLaravelGoogleTextToSpeech
{
    public function __construct(protected AdapterInterface $adapter) {}
}
