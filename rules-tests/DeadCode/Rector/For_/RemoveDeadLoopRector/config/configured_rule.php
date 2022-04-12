<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\For_\RemoveDeadLoopRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveDeadLoopRector::class);
};
