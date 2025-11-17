<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech\Tests;

use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SynthesizeSpeechResponse;
use Mockery\MockInterface;
use MoeMizrak\LaravelGoogleTextToSpeech\Adapters\AdapterInterface;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\CloudAudioConfigData;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\CloudSynthesizeData;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\CloudTextData;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\CloudVoiceData;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\GeminiAudioConfigData;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\GeminiSynthesizeData;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\GeminiTextData;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\GeminiVoiceData;
use MoeMizrak\LaravelGoogleTextToSpeech\Enums\TextToSpeechDriverType;
use MoeMizrak\LaravelGoogleTextToSpeech\Facades\GoogleTextToSpeech;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;

final class LaravelGoogleTextToSpeechTest extends TestCase
{
    private function mockCloudSynthesizeSpeechRequest(): void
    {
        $mockResponse = new SynthesizeSpeechResponse;
        $mockResponse->setAudioContent('some-audio-content-base64-encoded');

        $this->mock(AdapterInterface::class, function (MockInterface $mock) use ($mockResponse) {
            $mock->shouldReceive('synthesizeSpeech')
                ->once()
                ->andReturn($mockResponse->getAudioContent());
        });
    }

    private function mockGeminiSynthesizeSpeechRequest(): void
    {
        $mockResponse = 'some-audio-content-base64-decoded';

        $this->mock(AdapterInterface::class, function (MockInterface $mock) use ($mockResponse) {
            $mock->shouldReceive('synthesizeSpeech')
                ->once()
                ->andReturn($mockResponse);
        });
    }

    private function mockListVoicesRequestForCloudAPI(): void
    {
        $result = [
            [
                'name' => 'en-US-Standard-A',
                'language_codes' => ['en-US'],
                'gender' => 1, // FEMALE
                'natural_sample_rate_hertz' => 24000,
            ],
            [
                'name' => 'en-GB-Standard-B',
                'language_codes' => ['en-GB'],
                'gender' => 2, // MALE
                'natural_sample_rate_hertz' => 22050,
            ],
        ];

        $this->mock(AdapterInterface::class, function (MockInterface $mock) use ($result) {
            $mock->shouldReceive('listVoices')
                ->once()
                ->andReturn($result);
        });
    }

    #[Test]
    public function it_lists_available_voices_successfully_for_cloud_api()
    {
        /* SETUP */
        $languageCode = 'en';
        config(['laravel-google-text-to-speech.driver' => TextToSpeechDriverType::CLOUD->value]);
        config(['laravel-google-text-to-speech.api_endpoint' => 'texttospeech.googleapis.com']);
        $this->mockListVoicesRequestForCloudAPI();

        /* EXECUTE */
        $response = GoogleTextToSpeech::listVoices($languageCode);

        /* ASSERT */
        $this->assertGreaterThan(0, count($response));
        $firstVoice = $response[0];
        $this->assertIsArray($firstVoice);
        $this->assertArrayHasKey('language_codes', $firstVoice);
        $this->assertArrayHasKey('name', $firstVoice);
        $this->assertArrayHasKey('gender', $firstVoice);
        $this->assertArrayHasKey('natural_sample_rate_hertz', $firstVoice);
        $this->assertEquals('en-US-Standard-A', $response[0]['name']);
        $this->assertEquals(['en-US'], $response[0]['language_codes']);
        $this->assertEquals(1, $response[0]['gender']); // FEMALE
        $this->assertEquals(24000, $response[0]['natural_sample_rate_hertz']);
        $this->assertEquals('en-GB-Standard-B', $response[1]['name']);
        $this->assertEquals(['en-GB'], $response[1]['language_codes']);
        $this->assertEquals(2, $response[1]['gender']); // MALE
        $this->assertEquals(22050, $response[1]['natural_sample_rate_hertz']);
    }

    #[Test]
    public function it_synthesizes_speech_successfully_for_cloud_api()
    {
        /* SETUP */
        $textData = new CloudTextData(
            text: 'Hello, this is a test synthesis.',
            isSsml: false,
        );
        $voiceData = new CloudVoiceData;
        $audioConfigData = new CloudAudioConfigData(
            audioEncoding: AudioEncoding::LINEAR16, // LINEAR16 for wav format
        );
        $cloudSynthesizeData = new CloudSynthesizeData(
            $textData,
            $voiceData,
            $audioConfigData
        );
        config(['laravel-google-text-to-speech.driver' => TextToSpeechDriverType::CLOUD->value]);
        config(['laravel-google-text-to-speech.api_endpoint' => 'texttospeech.googleapis.com']);
        $this->mockCloudSynthesizeSpeechRequest();

        /* EXECUTE */
        $response = GoogleTextToSpeech::synthesizeSpeech($cloudSynthesizeData);

        /* ASSERT */
        $this->assertIsString($response);
        $this->assertEquals('some-audio-content-base64-encoded', $response);
    }

    #[Test]
    public function it_throws_exception_when_listing_voices_with_gemini_driver()
    {
        /* SETUP */
        $languageCode = 'en';
        config(['laravel-google-text-to-speech.driver' => TextToSpeechDriverType::GEMINI->value]);
        config(['laravel-google-text-to-speech.api_endpoint' => 'generativelanguage.googleapis.com']);

        /* ASSERT */
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Listing voices is not supported for Gemini API.');

        /* EXECUTE */
        GoogleTextToSpeech::listVoices($languageCode);
    }

    #[Test]
    public function it_synthesizes_speech_successfully_for_gemini_api()
    {
        /* SETUP */
        $textData = new GeminiTextData(
            text: 'Hello Jeniffer, this is a test synthesis using Gemini AI.',
        );
        $voiceData = new GeminiVoiceData;
        $audioConfigData = new GeminiAudioConfigData;
        $geminiSynthesizeData = new GeminiSynthesizeData(
            $textData,
            $voiceData,
            $audioConfigData
        );
        config(['laravel-google-text-to-speech.driver' => TextToSpeechDriverType::GEMINI->value]);
        config(['laravel-google-text-to-speech.api_endpoint' => 'generativelanguage.googleapis.com']);
        config(['laravel-google-text-to-speech.gemini.api_key' => 'AIzaSyCRHiimIOdb74HGLm7MEo3_WMtPnEXxzyc']);
        config(['laravel-google-text-to-speech.gemini.temperature' => 0.85]);
        config(['laravel-google-text-to-speech.gemini.model' => 'gemini-2.5-flash-preview-tts']);
        $this->mockGeminiSynthesizeSpeechRequest();

        /* EXECUTE */
        $response = GoogleTextToSpeech::synthesizeSpeech($geminiSynthesizeData);

        /* ASSERT */
        $this->assertIsString($response);
        $this->assertEquals('some-audio-content-base64-decoded', $response);
    }
}
