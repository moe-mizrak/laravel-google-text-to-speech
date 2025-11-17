<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech;

use Google\ApiCore\ValidationException;
use Google\Cloud\TextToSpeech\V1\Client\TextToSpeechClient;
use Illuminate\Support\ServiceProvider;
use MoeMizrak\LaravelGoogleTextToSpeech\Adapters\AdapterInterface;
use MoeMizrak\LaravelGoogleTextToSpeech\Adapters\CloudAdapterInterface;
use MoeMizrak\LaravelGoogleTextToSpeech\Adapters\CloudClientAdapter;
use MoeMizrak\LaravelGoogleTextToSpeech\Adapters\GeminiClientAdapter;
use MoeMizrak\LaravelGoogleTextToSpeech\Enums\TextToSpeechDriverType;

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
            return $this->cloudTextToSpeechClient();
        });

        // Bind the CloudAdapterInterface to the appropriate client.
        $this->app->bind(CloudAdapterInterface::class, function ($app) {
            return $app->make(TextToSpeechClient::class);
        });

        $this->app->bind(AdapterInterface::class, function ($app) {
            $driver = config('laravel-google-text-to-speech.driver');

            return match ($driver) {
                TextToSpeechDriverType::CLOUD->value => $app->make(CloudClientAdapter::class),
                TextToSpeechDriverType::GEMINI->value => $app->make(GeminiClientAdapter::class),
                default => throw new \InvalidArgumentException("Unsupported driver: {$driver}"),
            };
        });
    }

    /**
     * Create and configure the TextToSpeechClient for Google Cloud Text-to-Speech.
     *
     * @throws ValidationException
     */
    private function cloudTextToSpeechClient(): TextToSpeechClient
    {
        $options = [
            'apiEndpoint' => config('laravel-google-text-to-speech.api_endpoint'),
            'transport' => 'rest',
            'credentials' => config('laravel-google-text-to-speech.cloud.credentials'),
        ];

        return new TextToSpeechClient($options);
    }
}
