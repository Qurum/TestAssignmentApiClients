<?php

declare(strict_types=1);

namespace OpenWeatherMapApiClient\Responses;

use OpenWeatherMapApiClient\Commands\AbstractCommand;
use OpenWeatherMapApiClient\Commands\GetMoscowCoordinatesCommand;
use OpenWeatherMapApiClient\Commands\GetWeatherCommand;
use OpenWeatherMapApiClient\Responses\Exceptions\CoordinatesException;
use OpenWeatherMapApiClient\Responses\Exceptions\WeatherException;
use Psr\Container\ContainerInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class ResponseFactory
{
    public function __construct(protected readonly ContainerInterface $container) {}

    public function forCommand(AbstractCommand $command, array $data): AbstractResponse
    {
        return match ($command::class) {
            GetMoscowCoordinatesCommand::class => $this->makeCoordinateResponse($data),
            GetWeatherCommand::class           => $this->makeWeatherResponse($data),
        };
    }

    protected function makeCoordinateResponse($data): CoordinatesResponse
    {
        if (empty($data)) {
            throw new CoordinatesException("Empty response", 0);
        }

        $firstCity = $data[0];

        try {
            v::arrayType()
                ->key('lat', v::floatType())
                ->key('lon', v::floatType())
                ->assert($firstCity);
        } catch (NestedValidationException $exception) {
            throw new CoordinatesException("Can't extract lat and lon from response", 0, $exception);
        }

        return new CoordinatesResponse(
            lat: $firstCity['lat'],
            lon: $firstCity['lon'],
        );
    }

    protected function makeWeatherResponse($data): WeatherResponse
    {
        try {
            v::arrayType()
                ->notEmpty()
                ->key('weather',
                    v::arrayType()
                        ->notEmpty()
                        ->each(
                            v::arrayType()
                                ->key('main', v::stringType())
                                ->key('description', v::stringType())
                        )
                )
                ->key('main',
                    v::arrayType()
                        ->key('temp', v::floatType())
                        ->key('feels_like', v::floatType())
                )
                ->assert($data);
        } catch (NestedValidationException $exception) {
            throw new WeatherException("Can't extract weather from response", 0, $exception);
        }

        return new WeatherResponse(
            main       : $data['weather'][0]['main'],
            description: $data['weather'][0]['description'],
            temp       : $data['main']['temp'],
            feelsLike  : $data['main']['feels_like']
        );
    }
}