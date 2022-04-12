<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\For_\RemoveDeadContinueRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveDeadContinueRector::class);
};
