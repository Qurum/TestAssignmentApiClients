<?php

declare(strict_types=1);

namespace CbrApiClient\Responses;

class CurrenciesResponse extends AbstractResponse
{
    public function __construct(
        public readonly \SimpleXMLElement $currencies,
    ) {}
}