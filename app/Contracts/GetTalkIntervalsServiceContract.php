<?php

namespace App\Contracts;

interface GetTalkIntervalsServiceContract
{
    public function execute(array $silenceSequence, array $silenceStarts, array $silenceEnds): array;
}
