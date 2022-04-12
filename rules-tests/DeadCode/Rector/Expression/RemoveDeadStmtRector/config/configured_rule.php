<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\Expression\RemoveDeadStmtRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveDeadStmtRector::class);
};
