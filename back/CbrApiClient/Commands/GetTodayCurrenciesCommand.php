<?php

declare(strict_types=1);

namespace CbrApiClient\Commands;

class GetTodayCurrenciesCommand extends AbstractCommand
{
    protected const CONFIG_SECTION_NAME = 'get_currencies';

    public function getQuery(): array
    {
        return [
            'date_req' => (new \DateTimeImmutable())->format('d/m/Y'),
        ];
    }
}