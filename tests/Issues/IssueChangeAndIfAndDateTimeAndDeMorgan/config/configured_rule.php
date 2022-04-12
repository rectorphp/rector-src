<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\BooleanNot\SimplifyDeMorganBinaryRector;
use Rector\CodeQuality\Rector\ClassMethod\DateTimeToDateTimeInterfaceRector;
use Rector\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(SimplifyDeMorganBinaryRector::class);
    $services->set(ChangeAndIfToEarlyReturnRector::class);
    $services->set(DateTimeToDateTimeInterfaceRector::class);
};
