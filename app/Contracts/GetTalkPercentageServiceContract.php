<?php

namespace App\Contracts;

interface GetTalkPercentageServiceContract
{
    public function execute(array $monologues, array $silenceDurations): float;
}

