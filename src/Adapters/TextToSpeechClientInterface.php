<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech\Adapters;

use Google\ApiCore\ApiException;
use Google\Cloud\TextToSpeech\V1\ListVoicesRequest;
use Google\Cloud\TextToSpeech\V1\ListVoicesResponse;
use Google\Cloud\TextToSpeech\V1\SynthesizeSpeechRequest;
use Google\Cloud\TextToSpeech\V1\SynthesizeSpeechResponse;

interface TextToSpeechClientInterface
{
    /**
     * @throws ApiException
     */
    public function listVoices(ListVoicesRequest $request): ListVoicesResponse;

    /**
     * @throws ApiException
     */
    public function synthesizeSpeech(SynthesizeSpeechRequest $request): SynthesizeSpeechResponse;
}
