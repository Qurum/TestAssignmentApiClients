<?php

declare(strict_types=1);

namespace Services;

use CbrApiClient\Commands\GetTodayCurrenciesCommand;
use CbrApiClient\Responses\CurrenciesResponse;
use OpenWeatherMapApiClient\Client as WeatherClient;
use CbrApiClient\Client as CbrClient;
use OpenWeatherMapApiClient\Commands\GetWeatherCommand;
use OpenWeatherMapApiClient\Responses\WeatherResponse;

class TestAssignmentService
{
    protected const XPATH_CURRENCIES = './Valute/CharCode[%s]/..';

    public function __construct(
        protected readonly WeatherClient             $weatherClient,
        protected readonly CbrClient                 $cbrClient,
        protected readonly GetWeatherCommand         $getWeatherCommand,
        protected readonly GetTodayCurrenciesCommand $getTodayCurrenciesCommand,
        protected readonly array                     $codes,
    ) {}

    public function getMoscowWeather(): WeatherDto
    {
        /** @var WeatherResponse $weatherResponse */
        $weatherResponse = $this->weatherClient->handle($this->getWeatherCommand);

        return new WeatherDto(
            main       : $weatherResponse->main,
            description: $weatherResponse->description,
            temp       : $weatherResponse->temp,
            feelsLike  : $weatherResponse->feelsLike
        );
    }

    public function getTodayExchangeRates()
    {
        /** @var CurrenciesResponse $cbrResponse */
        $cbrResponse = $this->cbrClient->handle($this->getTodayCurrenciesCommand);
        $codes       = array_keys($this->codes);
        $codes       = array_map(static fn(string $code) => sprintf("normalize-space() = '%s'", $code), $codes);
        $query       = sprintf(self::XPATH_CURRENCIES, join(' or ', $codes));
        $currencies  = $cbrResponse->currencies->xpath($query);

        $result = [];
        foreach ($currencies as $currency) {
            $result[(string)$currency->CharCode] = new CurrencyDto(
                code       : (string)$currency->CharCode,
                nominal    : (float)$currency->Nominal,
                value      : (float)$currency->Value,
                description: $this->codes[(string)$currency->CharCode],
            );
        }

        return $result;
    }
}