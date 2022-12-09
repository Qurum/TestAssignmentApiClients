<?php

declare(strict_types=1);

namespace CbrApiClient\Responses;

use CbrApiClient\Commands\AbstractCommand;
use CbrApiClient\Commands\GetTodayCurrenciesCommand;
use CbrApiClient\Responses\Exceptions\CurrenciesException;
use Exception;
use SimpleXMLElement;

class ResponseFactory
{
    public function __construct() {}

    public function forCommand(AbstractCommand $command, string $data): AbstractResponse
    {
        return match ($command::class) {
            GetTodayCurrenciesCommand::class => $this->makeCurrenciesResponse($data),
        };
    }

    protected function makeCurrenciesResponse(string $data): CurrenciesResponse
    {
        try {
            $response = new SimpleXMLElement($data);
        } catch (Exception $exception) {
            throw new CurrenciesException("Invalid XML", 0, $exception);
        }

        return new CurrenciesResponse(
            $response
        );
    }
}