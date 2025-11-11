<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech;

use Google\ApiCore\ApiException;
use Google\Protobuf\RepeatedField;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\AudioConfigData;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\TextData;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\VoiceData;

final readonly class LaravelGoogleTextToSpeech extends AbstractLaravelGoogleTextToSpeech
{
    /**
     * Synthesize speech from the provided text, voice, and audio configuration data.
     *
     * @return string The synthesized audio content in base64-encoded format.
     *
     * @throws ApiException
     */
    public function synthesizeSpeech(
        TextData $textData,
        VoiceData $voiceData,
        AudioConfigData $audioConfigData,
    ): string {
        // Prepare the speech synthesis request with text, voice, and audio config
        $request = $this->requestHelper->prepareSpeechRequest($textData, $voiceData, $audioConfigData);

        // Call the Google Text-to-Speech API to synthesize speech synchronously
        $response = $this->client->synthesizeSpeech($request);

        return $response->getAudioContent();
    }

    /**
     * Lists the available voices for synthesis, filtered by language code if provided.
     *
     * @param string|null $languageCode The language code to filter voices (e.g., 'en', 'en-US' etc.). If null, all voices are returned.
     * @param bool $asArray Whether to return the voices as an array. If false, returns as RepeatedField which is iterable Voice objects.
     *
     * @return RepeatedField|array A list of available voices.
     *
     * @throws ApiException
     */
    public function listVoices(?string $languageCode = 'en', bool $asArray = true): RepeatedField|array
    {
        // Prepare the list voices request with optional language code filter
        $request = $this->requestHelper->prepareListVoicesRequest($languageCode);

        // Call the Google Text-to-Speech API to list available voices
        $response = $this->client->listVoices($request);

        $voices = $response->getVoices();

        return $asArray ? $this->requestHelper->voicesToArray($voices) : $voices;
    }
}
