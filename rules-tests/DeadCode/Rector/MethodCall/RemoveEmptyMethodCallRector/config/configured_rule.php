<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\MethodCall\RemoveEmptyMethodCallRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveEmptyMethodCallRector::class);
};
