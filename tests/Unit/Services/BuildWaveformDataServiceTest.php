<?php

namespace Tests\Unit\Services;

use App\Contracts\GetTalkIntervalsServiceContract;
use App\Contracts\GetTalkPercentageServiceContract;
use App\Services\BuildWaveformDataService;
use Tests\TestCase;

class BuildWaveformDataServiceTest extends TestCase
{
    private BuildWaveformDataService $service;
    private $getTalkIntervalsService;
    private $getTalkPercentageService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->getTalkIntervalsService =
            $this->createMock(GetTalkIntervalsServiceContract::class);
        $this->getTalkPercentageService =
            $this->createMock(GetTalkPercentageServiceContract::class);
    }

    public function initService()
    {
        $this->service = new BuildWaveformDataService(
            $this->getTalkIntervalsService,
            $this->getTalkPercentageService
        );
    }

    public function testExecute(): void
    {
        $this->initService();
        $fileName = 'test_audio_file';
        $expectedTalkIntervals = [[0, 1.84], [4.48, 26.928], [29.184, 29.36]];
        $expectedTalkPercentage = 50.0;

        $this->getTalkIntervalsService->expects($this->any())->method('execute')
            ->willReturn($expectedTalkIntervals);
        $this->getTalkPercentageService->expects($this->any())->method('execute')
            ->willReturn($expectedTalkPercentage);

        $actual = $this->service->execute($fileName);

        $expected = [
            'longest_user_monologue' => 22.45,
            'longest_customer_monologue' => 22.45,
            'user_talk_percentage' => $expectedTalkPercentage,
            'customer_talk_percentage' => $expectedTalkPercentage,
            'user' => $expectedTalkIntervals,
            'customer' => $expectedTalkIntervals,
        ];

        $this->assertEquals($expected, $actual);
    }

    public function testGetIntervals(): void
    {
        $this->initService();

        $fileContent = '[silencedetect @ 0x7fa7edd0c160] silence_start: 1.84
            [silencedetect @ 0x7fa7edd0c160] silence_end: 4.48 | silence_duration: 2.64';

        $expectedTalkIntervals = [
            [
                'silence_start: 1.84',
                'silence_end: 4.48',
                'silence_duration: 2.64',
            ],
            ['1.84', '', ''],
            ['', '4.48', ''],
            $expectedSlenceDurations = ['', '', '2.64']
        ];

        $this->getTalkIntervalsService->method('execute')
            ->willReturn($expectedTalkIntervals);

        $actual = $this->invokePrivateMethod(
            'getIntervals',
            $this->service,
            [$fileContent]
        );

        $expected = [$expectedSlenceDurations, $expectedTalkIntervals];

        $this->assertEquals($expected, $actual);
    }

    public function testGetFileContent(): void
    {
        $this->initService();
        $filename = 'test_audio_file/user_channel';

        $actual = $this->invokePrivateMethod(
            'getFileContent',
            $this->service,
            [$filename]
        );

        $expected = "[silencedetect @ 0x7fbfbbc076a0] silence_start: 3.504
[silencedetect @ 0x7fbfbbc076a0] silence_end: 6.656 | silence_duration: 3.152";

        $this->assertEquals($expected, $actual);
    }

    public function testExtractSilenceNumbers(): void
    {
        $this->initService();

        $fileContent = '[silencedetect @ 0x7fa7edd0c160] silence_start: 1.84
            [silencedetect @ 0x7fa7edd0c160] silence_end: 4.48 | silence_duration: 2.64';

        $actual = $this->invokePrivateMethod(
            'extractSilenceNumbers',
            $this->service,
            [$fileContent]
        );

        $expected = [
            [
                'silence_start: 1.84',
                'silence_end: 4.48',
                'silence_duration: 2.64',
            ],
            ['1.84', '', ''],
            ['', '4.48', ''],
            ['', '', '2.64']
        ];

        $this->assertEquals($expected, $actual);
    }

    public function testGetMonologues(): void
    {
        $this->initService();
        $talkIntervals = [[0, 3.504], [6.656, 14], [19.712, 20.144]];

        $actual = $this->invokePrivateMethod(
            'getMonologues',
            $this->service,
            [$talkIntervals]
        );

        $expected = [3.504, 7.344, 0.4319999999999986];

        $this->assertEquals($expected, $actual);
    }
}
