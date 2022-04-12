<?php

declare(strict_types=1);

use Rector\EarlyReturn\Rector\If_\ChangeNestedIfsToEarlyReturnRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ChangeNestedIfsToEarlyReturnRector::class);
};
