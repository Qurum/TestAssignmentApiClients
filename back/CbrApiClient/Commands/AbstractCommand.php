<?php

declare(strict_types=1);

namespace CbrApiClient\Commands;

use CbrApiClient\Commands\Exceptions\InvalidConfigException;
use Noodlehaus\Config;
use CbrApiClient\HttpMethod;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;
use RuntimeException;

abstract class AbstractCommand
{
    protected const CONFIG_SECTION_NAME = '';

    public function __construct(protected readonly Config $config)
    {
        if (empty(static::CONFIG_SECTION_NAME)) {
            throw new RuntimeException();
        }

        $this->validate($this->config->get(static::CONFIG_SECTION_NAME));
    }

    public function getMethod(): HttpMethod
    {
        return HttpMethod::from($this->config->get(static::CONFIG_SECTION_NAME . '.method'));
    }

    public function getPath(): string
    {
        return $this->config->get(static::CONFIG_SECTION_NAME . '.url');
    }

    public function getQuery(): array
    {
        return [];
    }

    protected function validate(mixed $config)
    {
        try {
            v::arrayType()
                ->key('method', v::stringType()->in(HttpMethod::values()))
                ->key('url', v::url())
                ->assert($config);
        } catch (NestedValidationException $exception) {
            throw new InvalidConfigException('Config file is invalid', 0, $exception);
        }
    }
}