<?php

declare(strict_types=1);

return [
    'api_endpoint' => env('GOOGLE_TEXT_TO_SPEECH_API_ENDPOINT', 'texttospeech.googleapis.com'),
    'credentials' => env('GOOGLE_TEXT_TO_SPEECH_CREDENTIALS'),
];
