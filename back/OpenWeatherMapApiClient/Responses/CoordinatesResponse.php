<?php

declare(strict_types=1);

namespace OpenWeatherMapApiClient\Responses;

class CoordinatesResponse extends AbstractResponse
{
    public function __construct(
        public readonly float $lat,
        public readonly float $lon,
    ) {}
}