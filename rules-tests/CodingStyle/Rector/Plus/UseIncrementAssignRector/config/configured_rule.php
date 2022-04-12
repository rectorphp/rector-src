<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Plus\UseIncrementAssignRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(UseIncrementAssignRector::class);
};
