<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\EarlyReturn\Rector\If_\ChangeOrIfReturnToEarlyReturnRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ExplicitBoolCompareRector::class);
    $services->set(ChangeOrIfReturnToEarlyReturnRector::class);
};
