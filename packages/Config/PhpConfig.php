<?php

declare(strict_types=1);

namespace Rector\Config;

/**
 * @api
 */
final class PhpConfig
{
    public function __construct(
        private readonly RectorConfig $rectorConfig
    ) {
    }

    public function latest(): self
    {
        // @todo discover from composer.json project
        // $this->rectorConfig->sets();
        return $this;
    }

    public function upToLatest(): self
    {
        // @todo discover from composer.json project
        // $this->rectorConfig->sets();
        return $this;
    }
}
