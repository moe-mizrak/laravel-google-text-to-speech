<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech\Data;

use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Spatie\LaravelData\Data;

/**
 * This DTO represents the audio configuration for Google Cloud Text-to-Speech.
 *
 * @see AudioConfig for more details.
 */
final class AudioConfigData extends Data
{
    public function __construct(
        public readonly int $audioEncoding = AudioEncoding::MP3,
        public readonly ?float $speakingRate = null,
        public readonly ?float $speakingPitch = null,
        public readonly ?float $volumeGainDb = null,
        public readonly ?int $sampleRateHertz = null,
    ) {}
}