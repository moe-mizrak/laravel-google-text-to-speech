<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech\Data;

use Spatie\LaravelData\Data;

/**
 * This DTO represents the voice selection parameters for Google Cloud Text-to-Speech.
 *
 * @see VoiceSelectionParams for more details.
 */
final class CloudVoiceData extends Data
{
    public function __construct(
        public readonly string $languageCode = 'en-US',
        public readonly ?string $voiceName = 'en-US-Wavenet-D',
        public readonly ?string $voiceCloningKey = null,
        public readonly ?string $modelName = null,
        public readonly ?CloudMultiSpeakerData $multiSpeakerData = null,
    ) {}
}
