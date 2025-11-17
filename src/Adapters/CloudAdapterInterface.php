<?php

declare(strict_types=1);

namespace MoeMizrak\LaravelGoogleTextToSpeech\Adapters;

use Google\ApiCore\ApiException;
use MoeMizrak\LaravelGoogleTextToSpeech\Data\SynthesizeData;

interface CloudAdapterInterface extends AdapterInterface
{
    /**
     * @throws ApiException
     */
    public function listVoices(?string $languageCode = null): array;

    /**
     * @throws ApiException
     */
    public function synthesizeSpeech(SynthesizeData $synthesizeData): string;
}
