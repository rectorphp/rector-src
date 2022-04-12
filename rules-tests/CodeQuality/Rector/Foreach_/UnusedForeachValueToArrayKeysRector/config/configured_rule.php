<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Foreach_\UnusedForeachValueToArrayKeysRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(UnusedForeachValueToArrayKeysRector::class);
};
