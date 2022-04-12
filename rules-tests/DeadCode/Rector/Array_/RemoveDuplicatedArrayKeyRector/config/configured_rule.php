<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\Array_\RemoveDuplicatedArrayKeyRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveDuplicatedArrayKeyRector::class);
};
