<?php

declare(strict_types=1);

use Rector\EarlyReturn\Rector\Foreach_\ReturnAfterToEarlyOnBreakRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ReturnAfterToEarlyOnBreakRector::class);
};
