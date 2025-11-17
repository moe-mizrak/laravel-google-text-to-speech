<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech\Enums;

/**
 * Enum representing the types of text-to-speech drivers.
 */
enum TextToSpeechDriverType: string
{
    case CLOUD = 'cloud';
    case GEMINI = 'gemini';
}
