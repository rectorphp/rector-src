<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\If_\NullableCompareToNullRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(NullableCompareToNullRector::class);
};
