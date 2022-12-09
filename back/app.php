<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/vendor/autoload.php';

$container = require_once __DIR__ . '/container.php';
/** @var \Services\TestAssignmentService $testAssignmentService */
$testAssignmentService = $container->get(\Services\TestAssignmentService::class);

$app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->get('/api/v1/testassignment',
    function (Request $request, Response $response, $args) use ($testAssignmentService) {
        try {
            $payload = [
                'status'         => 'success',
                'moscow_weather' => $testAssignmentService->getMoscowWeather(),
                'rates'          => $testAssignmentService->getTodayExchangeRates(),
            ];
        } catch (Exception $e) {
            $payload = [
                'status'  => 'error',
                'message' => $e->getMessage(),
            ];
        }

        $response->getBody()->write(json_encode($payload));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    });

$app->run();