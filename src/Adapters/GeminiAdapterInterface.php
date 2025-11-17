<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech\Adapters;

use Google\ApiCore\ApiException;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\SynthesizeData;

interface GeminiAdapterInterface extends AdapterInterface
{
    /**
     * @throws ApiException
     */
    public function synthesizeSpeech(
        SynthesizeData $synthesizeData
    ): string;
}
