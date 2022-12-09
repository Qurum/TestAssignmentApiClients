<?php

declare(strict_types=1);

namespace CbrApiClient;

enum HttpMethod: string
{
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';

    public static function values(): array
    {
        return array_map(static fn(HttpMethod $method) => $method->value, self::cases());
    }
}