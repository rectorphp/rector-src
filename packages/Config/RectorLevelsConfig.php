<?php

declare(strict_types=1);

namespace Rector\Config;

/**
 * @api
 */
final class RectorLevelsConfig
{
    public function __construct(
        private readonly RectorConfig $rectorConfig,
    ) {
    }

    public function latestSymfony(): void
    {
        // @todo resolve latest set from composer.json
        $this->rectorConfig->sets([
            // ...
        ]);
    }

    public function latestPHP(): void
    {
        // @todo resolve latest set from composer.json
        $this->rectorConfig->sets([
            // ...
        ]);
    }

    public function latestLaravel(): void
    {
        // @todo detect external package install - https://github.com/driftingly/rector-laravel
        // @todo resolve latest set from composer.json
        $this->rectorConfig->sets([
            // ...
        ]);
    }
}
