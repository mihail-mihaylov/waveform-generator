<?php

use App\Lib\Data;
use App\Services\GetTalkPercentageService;
use PHPUnit\Framework\TestCase;

class GetTalkPercentageServiceTest extends TestCase
{
    private GetTalkPercentageService $service;

    public function initService()
    {
        $this->service = new GetTalkPercentageService();
    }

    public function testExecute()
    {
        $this->initService();

        $monologues = [3.152, 5.712, 7.12, 5.2, 2.512];
        $silenceDurations = [3.504, 7.344, 0.432, 9.264, 5.552];

        $actual = $this->service->execute($monologues, $silenceDurations);
        $expected = 47.59;

        $this->assertEquals($expected, $actual);
    }
}
