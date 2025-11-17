<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech\Data;

use Spatie\LaravelData\Data;

/**
 * This DTO represents the audio configuration for Gemini Text-to-Speech.
 */
final class GeminiAudioConfigData extends Data
{
    public function __construct(
        public ?float $temperature = null,
    ) {
        $this->temperature ??= config('laravel-google-text-to-speech.gemini.temperature');
    }
}
