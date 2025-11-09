<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MoeMizrak\LaravelGoogleTextToSpeech\LaravelGoogleTextToSpeech
 */
final class LaravelGoogleTextToSpeech extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return \MoeMizrak\LaravelGoogleTextToSpeech\LaravelGoogleTextToSpeech::class;
    }
}