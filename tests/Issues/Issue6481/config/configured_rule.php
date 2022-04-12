<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\DeadCode\Rector\If_\RemoveDeadInstanceOfRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(EncapsedStringsToSprintfRector::class);
    $services->set(RemoveDeadInstanceOfRector::class);
};
