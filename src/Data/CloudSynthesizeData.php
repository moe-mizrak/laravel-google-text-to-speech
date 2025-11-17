<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech\Data;

/**
 * This DTO represents the data required for synthesizing speech using Google Cloud Text-to-Speech API.
 */
final class CloudSynthesizeData extends SynthesizeData
{
    public function __construct(
        public readonly CloudTextData $cloudTextData,
        public readonly CloudVoiceData $cloudVoiceData,
        public readonly CloudAudioConfigData $cloudAudioConfigData,
    ) {}
}
