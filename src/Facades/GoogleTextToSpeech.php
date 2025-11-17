<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech\Facades;

use Illuminate\Support\Facades\Facade;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\SynthesizeData;
use MoeMizrak\LaravelGoogleTextToSpeech\LaravelGoogleTextToSpeech;

/**
 * Facade for Laravel Google Text-to-Speech functionality.
 *
 * @method static string synthesizeSpeech(SynthesizeData $synthesizeData)
 * @method static array listVoices(?string $languageCode = 'en')
 *
 * @see LaravelGoogleTextToSpeech
 */
final class GoogleTextToSpeech extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return LaravelGoogleTextToSpeech::class;
    }
}
