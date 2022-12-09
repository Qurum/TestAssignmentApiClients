<?php

declare(strict_types=1);

namespace Services;

class WeatherDto
{
    public function __construct(
        public readonly string $main,
        public readonly string $description,
        public readonly float  $temp,
        public readonly float  $feelsLike,
    ) {}
}