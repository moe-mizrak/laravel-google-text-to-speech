# Laravel Google Cloud Text-to-Speech API Package
Laravel package for integrating **Google Cloud Text-to-Speech API**

## Requirements
- **PHP**: 8.4 or higher
- [Google Cloud Text-to-Speech API](https://console.cloud.google.com/apis/library/texttospeech.googleapis.com) **enabled** in your Google Cloud project.
- [Service Account Credentials JSON](https://console.cloud.google.com/apis/api/texttospeech.googleapis.com/credentials) file with proper permissions for **Text-to-Speech API**.

## Installation
You can install the package via composer:
  ```bash
  composer require moe-mizrak/laravel-google-text-to-speech
  ```

You can publish the config file with:
  ```bash
  php artisan vendor:publish --tag="laravel-google-text-to-speech"
  ```

## Configuration
After publishing the configuration file, you can set your Google Cloud credentials and other settings in the `config/laravel-google-text-to-speech.php` file.

Published config file will look like this:
```php
return [
    'api_endpoint' => env('GOOGLE_TEXT_TO_SPEECH_API_ENDPOINT', 'texttospeech.googleapis.com'),
    'credentials' => env('GOOGLE_TEXT_TO_SPEECH_CREDENTIALS'),
];
```

- `GOOGLE_TEXT_TO_SPEECH_API_ENDPOINT`: The API endpoint for Google Cloud Text-to-Speech. Default is `texttospeech.googleapis.com`.
- `GOOGLE_TEXT_TO_SPEECH_CREDENTIALS`: The path to your Google Cloud service account credentials JSON file.

> [!NOTE]
> Go to the [Google Cloud Console](https://console.cloud.google.com/apis/api/texttospeech.googleapis.com/credentials) to create and download your service account credentials with proper permissions for **Text-to-Speech API**.

## Usage
There are 2 methods:
- `synthesizeText`: Synthesize speech from plain text.
- `listVoices`: List available voices.

### Synthesize Text
This is an example of how to use the `synthesizeText` method:

```php
$textData = new TextData(
    text: 'Laplace Demon: the hypothetical entity that, with perfect knowledge of the present, could predict all future events based on causal determinism.',
    isSsml: false,
);

$voiceData = new VoiceData(
    languageCode: 'en-US',
    voiceName: 'en-US-Wavenet-D',
);

$audioConfigData = new AudioConfigData(
    audioEncoding: AudioEncoding::MP3,
);

$response = GoogleTextToSpeech::synthesizeSpeech($textData, $voiceData, $audioConfigData);
```

- `$response` will contain the synthesized audio content. it can be saved as an audio file as follows:

  ```php
  file_put_contents('output.mp3', $response);
  ```

> [!TIP]
> Check [`TextData`](src/Data/CloudTextData.php), [`VoiceData`](src/Data/CloudVoiceData.php), and [`AudioConfigData`](src/Data/CloudAudioConfigData.php) classes for more options.

### List Voices
This is an example of how to use the `listVoices` method:

```php
$response = GoogleTextToSpeech::listVoices(languageCode: 'en-US');
```

- `$response` will contain a list/array of available voices for the specified language code.

## Contributing

> **Your contributions are welcome!** If you'd like to improve this project, simply create a pull request with your changes. Your efforts help enhance its functionality and documentation.

> If you find this project useful, please consider ⭐ it to show your support!

## Authors
This project is created and maintained by [Moe Mizrak](https://github.com/moe-mizrak).

## License
Laravel Package Template is an open-sourced software licensed under the **[MIT license](LICENSE)**.
