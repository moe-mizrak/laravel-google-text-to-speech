<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use MoeMizrak\LaravelGoogleTextToSpeech\LaravelGoogleTextToSpeechServiceProvider;

/**
 * Base test case for the package.
 */
class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
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