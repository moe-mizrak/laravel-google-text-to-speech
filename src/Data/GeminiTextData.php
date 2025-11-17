<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech\Data;

use Spatie\LaravelData\Data;

/**
 * This DTO represents the text input for Gemini Text-to-Speech.
 */
final class GeminiTextData extends Data
{
    public function __construct(
        public readonly string $text, // The text content which can be plain text or SSML (no need for isSsml flag)
    ) {}
}
