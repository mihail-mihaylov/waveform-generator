<?php

namespace App\Controllers;

use App\Services\BuildWaveformDataService;

class WaveformController
{
    public function __construct(
        private BuildWaveformDataService $tempService
    ) {
    }

    public function index(string $requestFileName): array
    {
        return $this->tempService->execute($requestFileName);
    }
}
