<?php

declare(strict_types=1);

namespace Rector\Config;

/**
 * @api
 */
final class PresetConfig
{
    public function __construct(
        private readonly RectorConfig $rectorConfig
    ) {
    }

    public function attributes(): AttributesConfig
    {
        return new AttributesConfig($this->rectorConfig);
    }
}
