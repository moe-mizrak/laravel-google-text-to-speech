<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech\Data;

use Spatie\LaravelData\Data;

/**
 * This DTO represents the voice parameters for Gemini Text-to-Speech.
 */
final class GeminiVoiceData extends Data
{
    public function __construct(
        public string $languageCode = 'en-US',
        public readonly string $voiceName = 'Algieba',
        public ?string $modelName = null,
    ) {
        $this->modelName ??= config('laravel-google-text-to-speech.gemini.model');
    }
}
