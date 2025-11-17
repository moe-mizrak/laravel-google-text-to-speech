<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech;

use Google\ApiCore\ApiException;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\SynthesizeData;
use MoeMizrak\LaravelGoogleTextToSpeech\Enums\TextToSpeechDriverType;
use RuntimeException;

final readonly class LaravelGoogleTextToSpeech extends AbstractLaravelGoogleTextToSpeech
{
    /**
     * Synthesize speech from the provided text, voice, and audio configuration data.
     *
     * @return string The synthesized audio content in binary format.
     *
     * @throws ApiException
     */
    public function synthesizeSpeech(SynthesizeData $synthesizeData): string
    {
        return $this->adapter->synthesizeSpeech($synthesizeData);
    }

    /**
     * Lists the available voices for synthesis, filtered by language code if provided.
     *
     * @param string|null $languageCode The language code to filter voices (e.g., 'en', 'en-US' etc.). If null, all voices are returned.
     *
     * @return array A list of available voices.
     *
     * @throws ApiException
     */
    public function listVoices(?string $languageCode = 'en'): array
    {
        if (config('laravel-google-text-to-speech.driver') === TextToSpeechDriverType::GEMINI->value) {
            throw new RuntimeException('Listing voices is not supported for Gemini API.');
        }

        return $this->adapter->listVoices($languageCode);
    }
}
