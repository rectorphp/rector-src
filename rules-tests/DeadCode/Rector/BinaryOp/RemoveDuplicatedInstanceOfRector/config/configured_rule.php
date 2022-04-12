<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\BinaryOp\RemoveDuplicatedInstanceOfRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveDuplicatedInstanceOfRector::class);
};
