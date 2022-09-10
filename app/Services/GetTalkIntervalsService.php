<?php

namespace App\Services;

use App\Contracts\GetTalkIntervalsServiceContract;
use App\Lib\Data;

class GetTalkIntervalsService implements GetTalkIntervalsServiceContract
{
    public function execute(array $silenceSequence, array $silenceStarts, array $silenceEnds): array
    {
        $silenceStarts = array_filter($silenceStarts);
        $silenceEnds = array_filter($silenceEnds);

        $allSilencePoints = array_merge($silenceStarts, $silenceEnds);
        $allSilencePoints = array_map('floatval', $allSilencePoints);
        sort($allSilencePoints);

        // Start counting from 0 when first value in file is silence_start
        if ($this->isSilenceStartFirst($silenceSequence)) {
            array_unshift($allSilencePoints, 0);
        }

        // Remove last value if it is silence_end, it is a never finished silence
        if ($this->isSilenceEndLast($silenceSequence)) {
            array_pop($allSilencePoints);
        }

        return array_chunk($allSilencePoints, 2);
    }

    private function isSilenceStartFirst(array $silenceSequence): bool
    {
        return str_contains(reset($silenceSequence), 'silence_start');
    }

    private function isSilenceEndLast(array $silenceSequence): bool
    {
        $lastSilenceSignal = end($silenceSequence);
        if (str_contains($lastSilenceSignal, 'silence_duration')) {
            $lastSilenceSignal = $silenceSequence[count($silenceSequence) - 2];
        }

        return str_contains($lastSilenceSignal, 'silence_end');
    }
}
