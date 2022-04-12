<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Switch_\SingularSwitchToIfRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(SingularSwitchToIfRector::class);
};
