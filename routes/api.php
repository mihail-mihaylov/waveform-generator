<?php

use App\Controllers\WaveformController;
use App\Lib\Request;
use App\Lib\Response;
use App\Lib\Router;
use App\Requests\WaveformRequest;
use App\Services\BuildWaveformDataService;
use App\Services\GetTalkIntervalsService;
use App\Services\GetTalkPercentageService;

Router::get('/', function (Request $request, Response $response) {
    echo 'Hello there :)';
});

Router::get('/waveforms/([a-zA-Z0-9_]*)', function (WaveformRequest $request, Response $response) {
    if ($request->isValid(['filename' => $request->params[0]])) {
        $getTalkIntervalsService = new GetTalkIntervalsService();
        $getTalkPercentageService = new GetTalkPercentageService();

        $buildWaveformDataService = new BuildWaveformDataService(
            $getTalkIntervalsService,
            $getTalkPercentageService
        );

        $data = (new WaveformController($buildWaveformDataService))->index($request->params[0]);
        $response->setStatusCode(Response::HTTP_CODE_OK);
        $response->setData($data);
        $response->setErrors([]);
    } else {
        $response->setStatusCode(Response::HTTP_CODE_UNPROCESSABLE_ENTITY);
        $response->setData([]);
        $response->setErrors($request->getErrors());
    }

    $response->toJSON();
});
