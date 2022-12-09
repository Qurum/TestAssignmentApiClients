<?php

declare(strict_types=1);

namespace Services;

class CurrencyDto
{
    public function __construct(
        public readonly string $code,
        public readonly float  $nominal,
        public readonly float  $value,
        public readonly string $description,
    ) {}
}