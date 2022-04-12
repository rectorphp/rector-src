<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\TryCatch\RemoveDeadTryCatchRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveDeadTryCatchRector::class);
};
