<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\ClassMethod\DateTimeToDateTimeInterfaceRector;
use Rector\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector;
use Rector\EarlyReturn\Rector\If_\ChangeOrIfContinueToMultiContinueRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(DateTimeToDateTimeInterfaceRector::class);
    $services->set(ChangeAndIfToEarlyReturnRector::class);
    $services->set(ChangeOrIfContinueToMultiContinueRector::class);
};
