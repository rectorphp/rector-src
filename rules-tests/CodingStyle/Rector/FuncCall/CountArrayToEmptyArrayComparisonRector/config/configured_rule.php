<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\FuncCall\CountArrayToEmptyArrayComparisonRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(CountArrayToEmptyArrayComparisonRector::class);
};
