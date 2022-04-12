<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveUnreachableStatementRector::class);
};
