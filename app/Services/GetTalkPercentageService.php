<?php

namespace App\Services;

use App\Contracts\GetTalkPercentageServiceContract;

class GetTalkPercentageService implements GetTalkPercentageServiceContract
{
    public function execute(array $monologues, array $silenceDurations): float
    {
        $talkDurationSum = array_sum($monologues);
        $silenceDurationSum = array_sum($silenceDurations);
        $conversationTime = $talkDurationSum + $silenceDurationSum;

        $talkPercentage = ($talkDurationSum / $conversationTime) * 100;

        return round($talkPercentage, 2);
    }
}
