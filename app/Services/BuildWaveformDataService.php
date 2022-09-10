<?php

namespace App\Services;

use App\Contracts\GetTalkIntervalsServiceContract;
use App\Contracts\GetTalkPercentageServiceContract;
use App\Lib\Data;

class BuildWaveformDataService
{
    private const SILENCE_DETECTOR_REGEX =
        '/silence_start: (\d+(?:\.\d+)?)|silence_end: (\d+(?:\.\d+)?)|silence_duration: (\d+(?:\.\d+)?)/';

    public function __construct(
        private GetTalkIntervalsServiceContract $getTalkIntervalsService,
        private GetTalkPercentageServiceContract $getTalkPercentageService
    ) {
    }

    public function execute(string $filename): array
    {
        // User
        $userFileContent = $this->getFileContent($filename . '/user_channel');
        list($userSilenceDurations, $userTalkIntervals) = $this->getIntervals($userFileContent);
        $userMonologues = $this->getMonologues($userTalkIntervals);

        // Customer
        $customerFileContent = $this->getFileContent($filename . '/customer_channel');
        list($customerSilenceDurations, $customerTalkIntervals) = $this->getIntervals($customerFileContent);
        $customerMonologues = $this->getMonologues($customerTalkIntervals);

        return [
            'longest_user_monologue' => round(max($userMonologues), 2),
            'longest_customer_monologue' => round(max($customerMonologues), 2),
            'user_talk_percentage' => $this->getTalkPercentageService->execute($userMonologues, $userSilenceDurations),
            'customer_talk_percentage' => $this->getTalkPercentageService->execute(
                $customerMonologues,
                $customerSilenceDurations
            ),
            'user' => $userTalkIntervals,
            'customer' => $customerTalkIntervals,
        ];
    }

    private function getIntervals(string $fileContent): array
    {
        $data = $this->extractSilenceNumbers($fileContent);

        $silenceSequence = $data[0];
        $silenceStarts = $data[1];
        $silenceEnds = $data[2];
        $silenceDurations = $data[3];

        $talkIntervals = $this->getTalkIntervalsService->execute(
            $silenceSequence,
            $silenceStarts,
            $silenceEnds
        );

        return [$silenceDurations, $talkIntervals];
    }

    private function getFileContent(string $fileName): string
    {
        $fileContent = '';
        $filePath = getcwd() . '/assets/' . $fileName;
        $handle = fopen($filePath, "r");

        if ($handle) {
            $fileContent = file_get_contents($filePath);
        }

        fclose($handle);

        return $fileContent;
    }

    private function extractSilenceNumbers(string $fileContent): array
    {
        $matches = [];
        preg_match_all(self::SILENCE_DETECTOR_REGEX, $fileContent, $matches);

        return $matches;
    }

    private function getMonologues(array $talkIntervals): array
    {
        $monologues = [];
        foreach ($talkIntervals as $talkInterval) {
            $monologues[] = $talkInterval[1] - $talkInterval[0];
        }

        return $monologues;
    }
}
