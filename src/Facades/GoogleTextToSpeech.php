<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech\Facades;

use Google\Protobuf\RepeatedField;
use Illuminate\Support\Facades\Facade;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\AudioConfigData;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\TextData;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\VoiceData;
use MoeMizrak\LaravelGoogleTextToSpeech\LaravelGoogleTextToSpeech;

/**
 * Facade for Laravel Google Text-to-Speech functionality.
 *
 * @method static string synthesizeSpeech(TextData $textData, VoiceData $voiceData, AudioConfigData $audioConfigData)
 * @method static RepeatedField|array listVoices(?string $languageCode = 'en', bool $asArray = true)
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
