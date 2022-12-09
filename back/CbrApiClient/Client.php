<?php

declare(strict_types=1);

namespace CbrApiClient;

use GuzzleHttp\Exception\GuzzleException;
use CbrApiClient\Commands\AbstractCommand;
use CbrApiClient\Exceptions\ClientException;
use CbrApiClient\Responses\AbstractResponse;
use CbrApiClient\Responses\ResponseFactory;

class Client
{
    public function __construct(
        protected readonly ResponseFactory $responseFactory,
    ) {}

    public function handle(AbstractCommand $command): AbstractResponse
    {
        try {
            $client   = new \GuzzleHttp\Client();
            $response = $client->request(
                method : $command->getMethod()->value,
                uri    : $command->getPath(),
                options: [
                    'query'  => $command->getQuery(),
                    'verify' => false,
                ]);
            $content  = $response->getBody()->getContents();

            return $this->responseFactory->forCommand($command, $content);
        } catch (GuzzleException $e) {
            throw new ClientException('ClientException', 0, $e);
        }
    }
}