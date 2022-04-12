<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\BooleanNot\SimplifyDeMorganBinaryRector;
use Rector\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector;
use Rector\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ChangeAndIfToEarlyReturnRector::class);
    $services->set(CompleteDynamicPropertiesRector::class);
    $services->set(SimplifyDeMorganBinaryRector::class);
};
