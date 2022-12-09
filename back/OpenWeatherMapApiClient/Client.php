<?php

declare(strict_types=1);

namespace OpenWeatherMapApiClient;

use GuzzleHttp\Exception\GuzzleException;
use OpenWeatherMapApiClient\Commands\AbstractCommand;
use OpenWeatherMapApiClient\Exceptions\ClientException;
use OpenWeatherMapApiClient\Responses\AbstractResponse;
use OpenWeatherMapApiClient\Responses\ResponseFactory;

class Client
{
    protected const API_KEY_QUERY_PARAM_NAME = 'appid';

    public function __construct(
        protected readonly ResponseFactory $responseFactory,
        protected readonly string          $apiKey,
    ) {}

    public function handle(AbstractCommand $command): AbstractResponse
    {
        try {
            $client   = new \GuzzleHttp\Client();
            $response = $client->request(
                method : $command->getMethod()->value,
                uri    : $command->getPath(),
                options: [
                    'query'  => [
                        ... $command->getQuery(),
                        self::API_KEY_QUERY_PARAM_NAME => $this->apiKey,
                    ],
                    'verify' => false,
                ]);
            $content  = json_decode($response->getBody()->getContents(), true);

            return $this->responseFactory->forCommand($command, $content);
        } catch (GuzzleException $e) {
            throw new ClientException('ClientException', 0, $e);
        }
    }
}