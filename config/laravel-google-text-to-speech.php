<?php

declare(strict_types=1);

use MoeMizrak\LaravelGoogleTextToSpeech\Enums\TextToSpeechDriverType;

return [
    /*
     * The driver to use for Google Text-to-Speech service.
     * Options are 'cloud' for standard Text-to-Speech API or 'gemini' for Gemini AI Text-to-Speech.
     */
    'driver' => env('GOOGLE_TEXT_TO_SPEECH_DRIVER', TextToSpeechDriverType::GEMINI->value),

    /*
     * The API endpoint for Google Text-to-Speech service.
     * For standard Text-to-Speech, use 'texttospeech.googleapis.com'.
     * For Gemini AI Text-to-Speech, use 'generativelanguage.googleapis.com'.
     */
    'api_endpoint' => env('GOOGLE_TEXT_TO_SPEECH_API_ENDPOINT', 'generativelanguage.googleapis.com'),

    'cloud' => [
        /*
         * The path to the Google Cloud credentials JSON file.
         * You can create and download this file from the Google Cloud Console.
         */
        'credentials' => env('GOOGLE_TEXT_TO_SPEECH_CREDENTIALS'),
    ],

    'gemini' => [
        /*
         * The API key for accessing Google Gemini Text-to-Speech service.
         * You can obtain an API key from the Google Cloud Console.
         */
        'api_key' => env('GOOGLE_GEMINI_API_KEY'),

        /*
         * The Gemini model to use for Text-to-Speech synthesis.
         */
        'model' => env('GOOGLE_GEMINI_MODEL', 'gemini-2.5-flash-preview-tts'),
    ],
];
