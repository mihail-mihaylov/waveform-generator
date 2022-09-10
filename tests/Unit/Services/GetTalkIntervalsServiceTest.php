<?php

namespace Tests\Unit\Services;

use App\Services\GetTalkIntervalsService;
use Tests\TestCase;

class GetTalkIntervalsServiceTest extends TestCase
{
    private GetTalkIntervalsService $service;

    public function initService()
    {
        $this->service = new GetTalkIntervalsService();
    }

    /**
     * @dataProvider executeDataProvider
     */
    public function testExecute(array $silenceSequence, array $silenceStarts, array $silenceEnds, array $expected): void
    {
        $this->initService();

        $actual = $this->service->execute($silenceSequence, $silenceStarts, $silenceEnds);
        $this->assertEquals($expected, $actual);
    }

    public function executeDataProvider(): array
    {
        return [
            [
                // silenceStart is first
                'silenceSequence' => [
                    'silence_start: 3.504',
                    'silence_end: 6.656',
                    'silence_duration: 3.152',
                    'silence_start: 14',
                    'silence_end: 19.712',
                ],
                'silenceStarts' => [
                    '3.504',
                    '',
                    '',
                    '14',
                    '',
                    '',
                    '20.144',
                    '',
                    '',
                    '36.528',
                    '',
                    '',
                    '47.28',
                ],
                'silenceEnds' => [
                    '',
                    '6.656',
                    '',
                    '',
                    '19.712',
                    '',
                    '',
                    '27.264',
                    '',
                    '',
                    '41.728',
                    '',
                    '',
                    '49.792',
                ],
                'expected' => [
                    [0, 3.504],
                    [6.656, 14],
                    [19.712, 20.144],
                    [27.264, 36.528],
                    [41.728, 47.28]
                ],
            ],
            [
                // silenceStart is not first
                'silenceSequence' => [
                    'silence_end: 6.656',
                    'silence_duration: 3.152',
                    'silence_start: 14',
                    'silence_end: 19.712',
                ],
                'silenceStarts' => [
                    '',
                    '',
                    '14',
                    '',
                    '',
                    '20.144',
                    '',
                    '',
                    '36.528',
                    '',
                    '',
                    '47.28',
                ],
                'silenceEnds' => [
                    '6.656',
                    '',
                    '',
                    '19.712',
                    '',
                    '',
                    '27.264',
                    '',
                    '',
                    '41.728',
                    '',
                    '',
                    '49.792',
                ],
                'expected' => [
                    [6.656, 14],
                    [19.712, 20.144],
                    [27.264, 36.528],
                    [41.728, 47.28]
                ],
            ],
            [
                // silenceEnd is not last
                'silenceSequence' => [
                    'silence_start: 3.504',
                    'silence_end: 6.656',
                    'silence_duration: 3.152',
                    'silence_start: 14',
                ],
                'silenceStarts' => [
                    '3.504',
                    '',
                    '',
                    '14',
                    '',
                    '',
                    '20.144',
                    '',
                    '',
                    '36.528',
                    '',
                    '',
                    '47.28',
                ],
                'silenceEnds' => [
                    '',
                    '6.656',
                    '',
                    '',
                    '19.712',
                    '',
                    '',
                    '27.264',
                    '',
                    '',
                    '41.728',
                    '',
                    '',
                ],
                'expected' => [
                    [0, 3.504],
                    [6.656, 14],
                    [19.712, 20.144],
                    [27.264, 36.528],
                    [41.728, 47.28]
                ],
            ]
        ];
    }

    /**
     * @dataProvider isSilenceStartFirstDataProvider
     */
    public function testIsSilenceStartFirst(array $silenceSequence, bool $expected): void
    {
        $this->initService();

        $actual = $this->invokePrivateMethod(
            'isSilenceStartFirst',
            $this->service,
            [$silenceSequence]
        );

        $this->assertEquals($expected, $actual);
    }

    public function isSilenceStartFirstDataProvider(): array
    {
        return [
            [
                'silenceSequence' => [
                    'silence_start: 3.504',
                    'silence_end: 6.656',
                    'silence_duration: 3.152'
                ],
                'expected' => true,
            ],
            [
                'silenceSequence' => [
                    'silence_end: 6.656',
                    'silence_start: 3.504',
                    'silence_duration: 3.152'
                ],
                'expected' => false,
            ],
            [
                'silenceSequence' => [
                    'silence_: 3.504',
                    'silence_end: 6.656',
                    'silence_duration: 3.152'
                ],
                'expected' => false,
            ],
        ];
    }

    /**
     * @dataProvider isSilenceEndLastDataProvider
     */
    public function testIsSilenceEndLast(array $silenceSequence, bool $expected)
    {
        $this->initService();

        $actual = $this->invokePrivateMethod(
            'isSilenceEndLast',
            $this->service,
            [$silenceSequence]
        );

        $this->assertEquals($expected, $actual);
    }

    public function isSilenceEndLastDataProvider(): array
    {
        return [
            [
                'silenceSequence' => [
                    'silence_start: 3.504',
                    'silence_end: 6.656',
                    'silence_duration: 3.152'
                ],
                'expected' => true,
            ],
            [
                'silenceSequence' => [
                    'silence_end: 6.656',
                    'silence_start: 3.504',
                    'silence_duration: 3.152'
                ],
                'expected' => false,
            ],
            [
                'silenceSequence' => [
                    'silence_start: 3.504',
                    'silence_end: 6.656',
                ],
                'expected' => true,
            ],
        ];
    }
}
