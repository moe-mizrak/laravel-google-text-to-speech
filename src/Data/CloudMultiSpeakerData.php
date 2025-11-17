<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech\Data;

use Spatie\LaravelData\Data;

/**
 * This DTO represents the multi-speaker voice configuration for Google Cloud Text-to-Speech.
 *
 * @property string|null $firstSpeakerVoice e.g. 'en-US-Wavenet-A'
 * @property string|null $secondSpeakerVoice e.g. 'en-US-Wavenet-C'
 *
 * @see MultiSpeakerVoiceConfig for more details.
 */
final class CloudMultiSpeakerData extends Data
{
    public function __construct(
        public readonly ?string $firstSpeakerVoice = null,
        public readonly ?string $secondSpeakerVoice = null,
    ) {}
}
