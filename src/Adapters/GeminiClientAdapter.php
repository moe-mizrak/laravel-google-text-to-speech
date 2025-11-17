<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech\Adapters;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\SynthesizeData;
use MoeMizrak\LaravelGoogleTextToSpeech\Helpers\TextToSpeechRequestHelper;

final readonly class GeminiClientAdapter implements GeminiAdapterInterface
{
    public function __construct(
        private TextToSpeechRequestHelper $requestHelper,
    ) {}

    /**
     * {@inheritDoc}
     */
    public function synthesizeSpeech(
        SynthesizeData $synthesizeData
    ): string {
        $payload = $this->requestHelper->prepareGeminiRequest(
            $synthesizeData->geminiTextData,
            $synthesizeData->geminiVoiceData,
            $synthesizeData->geminiAudioConfigData,
        );

        $url = $this->requestHelper->buildGeminiGenerateContentUrl($synthesizeData->geminiVoiceData->modelName);
        $apiKey = config('laravel-google-text-to-speech.gemini.api_key');

        $response = Http::withHeaders([
            'x-goog-api-key' => $apiKey,
            'Content-Type' => 'application/json',
        ])->post($url, $payload);

        $encodedResponse = Arr::get($response->json(), 'candidates.0.content.parts.0.inlineData.data');

        return base64_decode($encodedResponse);
    }
}
