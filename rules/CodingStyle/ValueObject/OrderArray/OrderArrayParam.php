<?php

declare(strict_types=1);

namespace Rector\CodingStyle\ValueObject\OrderArray;

use Rector\CodingStyle\Rector\ClassMethod\OrderArrayParamRector;
use Rector\Core\Validation\RectorAssert;

final class OrderArrayParam
{
    /**
     * @param array<string, string> $config
     */
    public function __construct(
        private readonly array $config,
    )
    {
        foreach ($this->config as $key => $value) {
            RectorAssert::className($key);
            RectorAssert::elementName(
                $value,
                '/' . OrderArrayParamRector::ASC . '|' . OrderArrayParamRector::DESC . '/',
                "string"
            );
        }
    }

    /**
     * @return array<string, string>
     */
    public function getConfig(): array
    {
        return $this->config;
    }
}
