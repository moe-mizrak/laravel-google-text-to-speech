<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech\Helpers;

use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\ListVoicesRequest;
use Google\Cloud\TextToSpeech\V1\MultispeakerPrebuiltVoice;
use Google\Cloud\TextToSpeech\V1\MultiSpeakerVoiceConfig;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\SynthesizeSpeechRequest;
use Google\Cloud\TextToSpeech\V1\Voice;
use Google\Cloud\TextToSpeech\V1\VoiceCloneParams;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;
use Google\Protobuf\RepeatedField;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\AudioConfigData;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\TextData;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\VoiceData;

/**
 * Helper class to prepare requests for Google Text-to-Speech API.
 */
final readonly class TextToSpeechRequestHelper
{
    public function __construct(
        protected SynthesisInput $input,
        protected VoiceSelectionParams $voiceParams,
        protected VoiceCloneParams $voiceCloneParams,
        protected MultiSpeakerVoiceConfig $multiSpeakerVoiceConfig,
        protected AudioConfig $audioConfig,
        protected SynthesizeSpeechRequest $speechRequest,
        protected ListVoicesRequest $listVoicesRequest,
    ) {}

    /**
     * Prepares the list voices request with an optional language code filter.
     *
     * @param string|null $languageCode The language code to filter voices (e.g., 'en', 'en-US' etc.). If null, all voices are returned.
     *
     * @return ListVoicesRequest The prepared list voices request.
     */
    public function prepareListVoicesRequest(?string $languageCode = 'en'): ListVoicesRequest
    {
        if ($languageCode) {
            $this->listVoicesRequest->setLanguageCode($languageCode);
        }

        return $this->listVoicesRequest;
    }

    /**
     * Prepares the speech synthesis request with text input, voice, and audio configuration.
     *
     * @param TextData $textData The text input data for synthesis.
     * @param VoiceData $voiceData The voice selection parameters.
     * @param AudioConfigData $audioConfigData The audio configuration parameters.
     *
     * @return SynthesizeSpeechRequest The prepared speech synthesis request.
     */
    public function prepareSpeechRequest(
        TextData $textData,
        VoiceData $voiceData,
        AudioConfigData $audioConfigData,
    ): SynthesizeSpeechRequest {
        // Set text input
        $textInput = $this->setTextInput($textData);

        // Set voice parameters
        $voice = $this->setVoiceParams($voiceData);

        // Set audio configuration
        $audioConfig = $this->setAudioConfig($audioConfigData);

        /*
         * Set the synthesis request with all parameters
         */
        return $this->speechRequest
            ->setInput($textInput)
            ->setVoice($voice)
            ->setAudioConfig($audioConfig);
    }

    /**
     * Converts a RepeatedField of Voice objects to an array representation.
     *
     * @param RepeatedField $voices The RepeatedField containing Voice objects.
     */
    public function voicesToArray(RepeatedField $voices): array
    {
        $voicesArray = [];

        foreach ($voices as $voice) {
            /** @var Voice $voice */
            $voicesArray[] = [
                'name' => $voice->getName(),
                'language_codes' => iterator_to_array($voice->getLanguageCodes()),
                'gender' => $voice->getSsmlGender(),
                'natural_sample_rate_hz' => $voice->getNaturalSampleRateHertz(),
            ];
        }

        return $voicesArray;
    }

    private function setVoiceParams(VoiceData $voiceData): VoiceSelectionParams
    {
        $this->voiceParams->setLanguageCode($voiceData->languageCode);

        // prioritize voice cloning if key is provided
        if ($voiceData->voiceCloningKey) {
            // Set the voice cloning parameters if provided
            $cloneParams = $this->voiceCloneParams->setVoiceCloningKey($voiceData->voiceCloningKey);
            $this->voiceParams->setVoiceClone($cloneParams);
        } elseif ($voiceData->multiSpeakerData) {
            $firstSpeaker = app(MultispeakerPrebuiltVoice::class); // resolve new instance from container for the first speaker
            $secondSpeaker = app(MultispeakerPrebuiltVoice::class); // resolve new instance from container for the second speaker

            $firstSpeaker->setVoiceName($voiceData->multiSpeakerData->firstSpeakerVoice);
            $secondSpeaker->setVoiceName($voiceData->multiSpeakerData->secondSpeakerVoice);

            $this->multiSpeakerVoiceConfig->setSpeakerVoiceConfigs([$firstSpeaker, $secondSpeaker]);

            $this->voiceParams->setMultiSpeakerVoiceConfig($this->multiSpeakerVoiceConfig);
        } else {
            // Set voice name
            $this->voiceParams->setName($voiceData->voiceName);
        }

        // Use custom model if provided e.g. "models/gemini-tts-1", auto selects default model otherwise
        if ($voiceData->modelName) {
            $this->voiceParams->setModelName($voiceData->modelName);
        }

        return $this->voiceParams;
    }

    private function setTextInput(TextData $textData): SynthesisInput
    {
        $text = $textData->text;
        $textData->isSsml ? $this->input->setSsml($text) : $this->input->setText($text);

        return $this->input;
    }

    private function setAudioConfig(AudioConfigData $audioConfigData): AudioConfig
    {
        $this->audioConfig->setAudioEncoding($audioConfigData->audioEncoding);

        if ($audioConfigData->speakingRate) {
            $this->audioConfig->setSpeakingRate($audioConfigData->speakingRate);
        }

        if ($audioConfigData->speakingPitch) {
            $this->audioConfig->setPitch($audioConfigData->speakingPitch);
        }

        if ($audioConfigData->volumeGainDb) {
            $this->audioConfig->setVolumeGainDb($audioConfigData->volumeGainDb);
        }

        if ($audioConfigData->sampleRateHertz) {
            $this->audioConfig->setSampleRateHertz($audioConfigData->sampleRateHertz);
        }

        return $this->audioConfig;
    }
}
