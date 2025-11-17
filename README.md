# Laravel Gemini and Google Cloud Text-to-Speech API Package
Laravel package for integrating **Gemini Text-to-Speech API** and **Google Cloud Text-to-Speech API**

## Requirements
- **PHP**: 8.4 or higher
- Google Cloud account with access to **Gemini API** and/or **Cloud Text-to-Speech API**

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
    'driver' => env('GOOGLE_TEXT_TO_SPEECH_DRIVER', TextToSpeechDriverType::GEMINI->value), // Options: 'gemini', 'cloud'
    'api_endpoint' => env('GOOGLE_TEXT_TO_SPEECH_API_ENDPOINT', 'generativelanguage.googleapis.com'), // For Gemini API use 'generativelanguage.googleapis.com', for Google Cloud API use 'texttospeech.googleapis.com'
    'cloud' => [
        'credentials' => env('GOOGLE_TEXT_TO_SPEECH_CREDENTIALS'), // The path to the Google Cloud credentials JSON file.
    ],
    'gemini' => [
        'api_key' => env('GOOGLE_GEMINI_API_KEY'), // Your Gemini API key
        'model' => env('GOOGLE_GEMINI_MODEL', 'gemini-2.5-flash-preview-tts'), // The Gemini model to use for Text-to-Speech synthesis.
    ],
];
```

> [!NOTE]
> If you are using **Google Cloud Text-to-Speech API**:
> - Go to the [Google Cloud Console](https://console.cloud.google.com/apis/api/texttospeech.googleapis.com/credentials) to create and download your service account credentials with proper permissions for **Text-to-Speech API**.
> - Save the downloaded JSON file and set its path in the config `cloud.credentials` field.
>
> If you are using **Gemini Text-to-Speech API**:
> - Go to [Google Cloud Console](https://console.cloud.google.com/projectselector2/iam-admin/serviceaccounts) and select the project where Gemini API is enabled (or create a project).
> - Create a service account with the necessary roles to access Gemini API.
> - Add a new key on the **Keys** tab, which will be used in the config `gemini.api_key` field.

## Usage
There are 2 drivers for Google Text-to-Speech API:
- `gemini`: Uses **Gemini Text-to-Speech API**.
- `cloud`: Uses **Google Cloud Text-to-Speech API**.

> Gemini Text-to-Speech API is the newer and more advanced API (premium voices), while Google Cloud Text-to-Speech API is the traditional API.

> [!NOTE]
> You can set the driver in the config file so that the package uses the desired API automatically
> 
> (You need to set credentials/api_key, and api_endpoint accordingly in the config file for the selected driver)

### Synthesize Text
This is an example of how to use the `synthesizeText` method:

##### For Gemini Text-to-Speech API:

```php
$textData = new GeminiTextData(
    text: 'Laplace Demon: the hypothetical entity that, with perfect knowledge of the present, could predict all future events based on causal determinism.',
);

$voiceData = new GeminiVoiceData(
    voiceName: 'Algieba',
    modelName: 'gemini-2.5-flash-preview-tts',
);

$geminiSynthesizeData = new CloudSynthesizeData(
    $textData,
    $voiceData,
);

$response = GoogleTextToSpeech::synthesizeSpeech($geminiSynthesizeData);
```

- `$response` will contain the synthesized audio content (bytes). it can be saved as an audio file as follows:

  ```php
  file_put_contents('output.pcm', $response);
  ```
  
> [!NOTE]
> Gemini Text-to-Speech API currently supports only **.pcm** audio format.
> 
> After saving the output as a `.pcm` file, you can convert it to other audio formats (like `.wav` or `.mp3`) using tools like `ffmpeg`.
> 
> For example, to convert the `.pcm` file to `.wav`, you can use the following `ffmpeg` command:
> ```bash
> ffmpeg -f s16le -ar 24000 -ac 1 -i output.pcm out.wav
> ```

> [!TIP]
> Check [`GeminiTextData`](src/Data/GeminiTextData.php), and [`CloudVoiceData`](src/Data/GeminiVoiceData.php) classes for more options.

#### For Cloud Text-to-Speech API:

```php
$textData = new CloudTextData(
    text: 'Laplace Demon: the hypothetical entity that, with perfect knowledge of the present, could predict all future events based on causal determinism.',
    isSsml: false,
);

$voiceData = new CloudVoiceData(
    languageCode: 'en-US',
    voiceName: 'en-US-Wavenet-D',
);

$audioConfigData = new CloudAudioConfigData(
    audioEncoding: AudioEncoding::MP3,
);

$cloudSynthesizeData = new CloudSynthesizeData(
    $textData,
    $voiceData,
    $audioConfigData
);

$response = GoogleTextToSpeech::synthesizeSpeech($cloudSynthesizeData);
```

- `$response` will contain the synthesized audio content (bytes). it can be saved as an audio file as follows:

  ```php
  file_put_contents('output.mp3', $response);
  ```

> [!TIP]
> Check [`CloudTextData`](src/Data/CloudTextData.php), [`CloudVoiceData`](src/Data/CloudVoiceData.php), and [`CloudAudioConfigData`](src/Data/CloudAudioConfigData.php) classes for more options.

### List Voices
This is an example of how to use the `listVoices` method:

```php
$response = GoogleTextToSpeech::listVoices(languageCode: 'en-US');
```

- `$response` will contain a list/array of available voices for the specified language code.

> [!WARNING]
> `listVoices` method only works with **Google Cloud Text-to-Speech API**. It is not supported for **Gemini Text-to-Speech API**.

## Contributing

> **Your contributions are welcome!** If you'd like to improve this project, simply create a pull request with your changes. Your efforts help enhance its functionality and documentation.

> If you find this project useful, please consider ⭐ it to show your support!

## Authors
This project is created and maintained by [Moe Mizrak](https://github.com/moe-mizrak).

## License
Laravel Package Template is an open-sourced software licensed under the **[MIT license](LICENSE)**.
