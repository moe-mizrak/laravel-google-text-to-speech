<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech\Data;

use Spatie\LaravelData\Data;

/**
 * This DTO represents the text input for Google Text-to-Speech including whether it's plain text or SSML etc.
 *
 * @see SynthesisInput for more details.
 */
final class CloudTextData extends Data
{
    public function __construct(
        public readonly string $text,
        public readonly bool $isSsml = false, // Indicates if the text is SSML or plain text where SSML is Speech Synthesis Markup Language format
    ) {}
}
