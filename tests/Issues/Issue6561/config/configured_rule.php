<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodingStyle\Rector\FuncCall\CountArrayToEmptyArrayComparisonRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ExplicitBoolCompareRector::class);
    $services->set(CountArrayToEmptyArrayComparisonRector::class);
};
