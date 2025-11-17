<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech\Adapters;

use Google\Cloud\TextToSpeech\V1\Client\TextToSpeechClient;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\SynthesizeData;
use MoeMizrak\LaravelGoogleTextToSpeech\Helpers\TextToSpeechRequestHelper;

/**
 * Adapter class for Google Text-to-Speech Client implementing the CloudAdapterInterface,
 * this wraps the actual Google Text-to-Speech Client so that we can mock it in tests.
 */
final readonly class CloudClientAdapter implements CloudAdapterInterface
{
    public function __construct(
        private TextToSpeechClient $client,
        private TextToSpeechRequestHelper $requestHelper,
    ) {}

    /**
     * {@inheritDoc}
     */
    public function listVoices(?string $languageCode = null): array
    {
        // Prepare the list voices request with optional language code filter
        $request = $this->requestHelper->prepareListVoicesRequest($languageCode);

        // Call the Google Text-to-Speech API to list available voices
        $response = $this->client->listVoices($request);

        $voices = $response->getVoices();

        return $this->requestHelper->voicesToArray($voices);
    }

    /**
     * {@inheritDoc}
     */
    public function synthesizeSpeech(
        SynthesizeData $synthesizeData
    ): string {
        // Prepare the speech synthesis request with text, voice, and audio config
        $request = $this->requestHelper->prepareCloudRequest(
            $synthesizeData->cloudTextData,
            $synthesizeData->cloudVoiceData,
            $synthesizeData->cloudAudioConfigData
        );

        // Call the Google Text-to-Speech API to synthesize speech synchronously
        $response = $this->client->synthesizeSpeech($request);

        return $response->getAudioContent();
    }
}
