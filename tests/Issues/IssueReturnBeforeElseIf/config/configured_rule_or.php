<?php

declare(strict_types=1);

use Rector\EarlyReturn\Rector\If_\ChangeOrIfReturnToEarlyReturnRector;
use Rector\EarlyReturn\Rector\If_\RemoveAlwaysElseRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ChangeOrIfReturnToEarlyReturnRector::class);
    $services->set(RemoveAlwaysElseRector::class);
};
