<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Switch_\BinarySwitchToIfElseRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(BinarySwitchToIfElseRector::class);
};
