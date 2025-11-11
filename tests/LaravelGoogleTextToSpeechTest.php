<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech\Tests;

use Google\ApiCore\GPBType;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\ListVoicesResponse;
use Google\Cloud\TextToSpeech\V1\SynthesizeSpeechResponse;
use Google\Cloud\TextToSpeech\V1\Voice;
use Google\Protobuf\RepeatedField;
use Mockery\MockInterface;
use MoeMizrak\LaravelGoogleTextToSpeech\Adapters\TextToSpeechClientInterface;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\AudioConfigData;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\TextData;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\VoiceData;
use MoeMizrak\LaravelGoogleTextToSpeech\Facades\GoogleTextToSpeech;
use PHPUnit\Framework\Attributes\Test;

final class LaravelGoogleTextToSpeechTest extends TestCase
{
    private function mockSynthesizeSpeechRequest(): void
    {
        $mockResponse = new SynthesizeSpeechResponse;
        $mockResponse->setAudioContent('some-audio-content-base64-encoded');

        $this->mock(TextToSpeechClientInterface::class, function (MockInterface $mock) use ($mockResponse) {
            $mock->shouldReceive('synthesizeSpeech')
                ->once()
                ->andReturn($mockResponse);
        });
    }

    private function mockListVoicesRequest(): void
    {
        /**
         * @var RepeatedField|\Google\Protobuf\Internal\RepeatedField<Voice> $voices
         */
        $voices = new RepeatedField(GPBType::MESSAGE, Voice::class);
        $voices[] = new Voice([
            'name' => 'en-US-Standard-A',
            'language_codes' => ['en-US'],
            'ssml_gender' => 1, // FEMALE
            'natural_sample_rate_hertz' => 24000,
        ]);
        $voices[] = new Voice([
            'name' => 'en-GB-Standard-B',
            'language_codes' => ['en-GB'],
            'ssml_gender' => 2, // MALE
            'natural_sample_rate_hertz' => 22050,
        ]);
        $mockResponse = new ListVoicesResponse;

        $mockResponse->setVoices($voices);

        $this->mock(TextToSpeechClientInterface::class, function (MockInterface $mock) use ($mockResponse) {
            $mock->shouldReceive('listVoices')
                ->once()
                ->andReturn($mockResponse);
        });
    }

    #[Test]
    public function it_lists_available_voices_successfully()
    {
        /* SETUP */
        $languageCode = 'en';
        $this->mockListVoicesRequest();

        /* EXECUTE */
        $response = GoogleTextToSpeech::listVoices($languageCode);

        /* ASSERT */
        $this->assertGreaterThan(0, count($response));
        $firstVoice = $response[0];
        $this->assertIsArray($firstVoice);
        $this->assertArrayHasKey('language_codes', $firstVoice);
        $this->assertArrayHasKey('name', $firstVoice);
        $this->assertArrayHasKey('gender', $firstVoice);
        $this->assertArrayHasKey('natural_sample_rate_hz', $firstVoice);
        $this->assertEquals('en-US-Standard-A', $response[0]['name']);
        $this->assertEquals(['en-US'], $response[0]['language_codes']);
        $this->assertEquals(1, $response[0]['gender']); // FEMALE
        $this->assertEquals(24000, $response[0]['natural_sample_rate_hz']);
        $this->assertEquals('en-GB-Standard-B', $response[1]['name']);
        $this->assertEquals(['en-GB'], $response[1]['language_codes']);
        $this->assertEquals(2, $response[1]['gender']); // MALE
        $this->assertEquals(22050, $response[1]['natural_sample_rate_hz']);
    }

    #[Test]
    public function it_lists_available_voices_successfully_when_as_array_is_false()
    {
        /* SETUP */
        $languageCode = 'en';
        $this->mockListVoicesRequest();

        /* EXECUTE */
        $response = GoogleTextToSpeech::listVoices($languageCode, asArray: false);

        /* ASSERT */
        $this->assertInstanceOf(RepeatedField::class, $response);
        $this->assertGreaterThan(0, $response->count());
        $firstVoice = $response[0];
        $this->assertNotNull($firstVoice->getName());
        $this->assertIsArray(iterator_to_array($firstVoice->getLanguageCodes()));
        $this->assertNotNull($firstVoice->getSsmlGender());
        $this->assertNotNull($firstVoice->getNaturalSampleRateHertz());
    }

    #[Test]
    public function it_synthesizes_speech_successfully()
    {
        /* SETUP */
        $textData = new TextData(
            text: 'Hello, this is a test synthesis.',
            isSsml: false,
        );
        $voiceData = new VoiceData;
        $audioConfigData = new AudioConfigData(
            audioEncoding: AudioEncoding::LINEAR16, // LINEAR16 for wav format
        );
        $this->mockSynthesizeSpeechRequest();

        /* EXECUTE */
        $response = GoogleTextToSpeech::synthesizeSpeech($textData, $voiceData, $audioConfigData);

        /* ASSERT */
        $this->assertIsString($response);
        $this->assertEquals('some-audio-content-base64-encoded', $response);
    }
}
