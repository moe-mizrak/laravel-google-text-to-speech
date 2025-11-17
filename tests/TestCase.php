<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech\Tests;

use MoeMizrak\LaravelGoogleTextToSpeech\LaravelGoogleTextToSpeechServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

/**
 * Base test case for the package.
 */
class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Set the path to the Google TTS credentials for testing (put your test credentials json file in the tests/storage folder)
        config(['laravel-google-text-to-speech.cloud.credentials' => __DIR__ . '/storage/google-tts-credentials.json']);
    }

    /**
     * @return string[]
     */
    protected function getPackageProviders($app): array
    {
        return [
            LaravelGoogleTextToSpeechServiceProvider::class,
        ];
    }
}
