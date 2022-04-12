<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveUnusedNonEmptyArrayBeforeForeachRector::class);
};
