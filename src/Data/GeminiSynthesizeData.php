<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech\Data;

/**
 * This DTO represents the data required for synthesizing speech using Gemini Text-to-Speech API.
 */
final class GeminiSynthesizeData extends SynthesizeData
{
    public function __construct(
        public readonly GeminiTextData $geminiTextData,
        public readonly GeminiVoiceData $geminiVoiceData,
    ) {}
}
