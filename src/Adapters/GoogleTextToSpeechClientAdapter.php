<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech\Adapters;

use Google\Cloud\TextToSpeech\V1\Client\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\ListVoicesRequest;
use Google\Cloud\TextToSpeech\V1\ListVoicesResponse;
use Google\Cloud\TextToSpeech\V1\SynthesizeSpeechRequest;
use Google\Cloud\TextToSpeech\V1\SynthesizeSpeechResponse;

/**
 * Adapter class for Google Text-to-Speech Client implementing the TextToSpeechClientInterface,
 * this wraps the actual Google Text-to-Speech Client so that we can mock it in tests.
 */
final readonly class GoogleTextToSpeechClientAdapter implements TextToSpeechClientInterface
{
    public function __construct(private TextToSpeechClient $client) {}

    /**
     * {@inheritDoc}
     */
    public function listVoices(ListVoicesRequest $request): ListVoicesResponse
    {
        return $this->client->listVoices($request);
    }

    /**
     * {@inheritDoc}
     */
    public function synthesizeSpeech(SynthesizeSpeechRequest $request): SynthesizeSpeechResponse
    {
        return $this->client->synthesizeSpeech($request);
    }
}
