<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech;

use Google\Cloud\TextToSpeech\V1\Client\TextToSpeechClient;
use Illuminate\Support\ServiceProvider;
use MoeMizrak\LaravelGoogleTextToSpeech\Adapters\GoogleTextToSpeechClientAdapter;
use MoeMizrak\LaravelGoogleTextToSpeech\Adapters\TextToSpeechClientInterface;

final class LaravelGoogleTextToSpeechServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPublishing();
    }

    public function register(): void
    {
        $this->configure();

        // Bind Google Text-to-Speech dependencies
        $this->bindGoogleTextToSpeechDependencies();
    }

    public function provides(): array
    {
        return ['laravel-google-text-to-speech'];
    }

    protected function configure(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravel-google-text-to-speech.php', 'laravel-google-text-to-speech'
        );
    }

    protected function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/laravel-google-text-to-speech.php' => config_path('laravel-google-text-to-speech.php'),
            ], 'laravel-google-text-to-speech');
        }
    }

    /**
     * Bind Google Text-to-Speech dependencies to the service container.
     */
    private function bindGoogleTextToSpeechDependencies(): void
    {
        $this->app->singleton(TextToSpeechClient::class, function () {
            return $this->textToSpeechClient();
        });

        // Bind the TextToSpeechClientInterface to the GoogleTextToSpeechClientAdapter so that we can mock it in tests
        $this->app->bind(TextToSpeechClientInterface::class, function ($app) {
            return new GoogleTextToSpeechClientAdapter(
                $app->make(TextToSpeechClient::class)
            );
        });
    }

    /**
     * Create and configure the TextToSpeechClient.
     */
    private function textToSpeechClient(): TextToSpeechClient
    {
        $options = [
            'apiEndpoint' => config('laravel-google-text-to-speech.api_endpoint'),
            'transport' => 'rest',
            'credentials' => config('laravel-google-text-to-speech.credentials'),
        ];

        return new TextToSpeechClient($options);
    }
}