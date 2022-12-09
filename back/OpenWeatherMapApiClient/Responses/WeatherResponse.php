<?php

declare(strict_types=1);

namespace OpenWeatherMapApiClient\Responses;

class WeatherResponse extends AbstractResponse
{
    public function __construct(
        public readonly string $main,
        public readonly string $description,
        public readonly float  $temp,
        public readonly float  $feelsLike,
    ) {}
}