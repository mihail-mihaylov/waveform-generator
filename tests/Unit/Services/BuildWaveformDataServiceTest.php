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

        $actual = $this->service->execute();
        $this->assertEquals($expected, $actual);
    }

    public function testGetIntervals()
    {
        $this->initService();

        $actual = $this->invokePrivateMethod(
            'getIntervals',
            $this->service,
            []
        );

        $this->assertEquals($expected, $actual);
    }

    public function testGetFileContent()
    {
        $this->initService();

        $actual = $this->invokePrivateMethod(
            'getFileContent',
            $this->service,
            [$silenceSequence]
        );

        $this->assertEquals($expected, $actual);
    }

    public function testExtractSilenceNumbers()
    {
        $this->initService();

        $actual = $this->invokePrivateMethod(
            'extractSilenceNumbers',
            $this->service,
            []
        );

        $this->assertEquals($expected, $actual);
    }

    public function testGetMonologues()
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
