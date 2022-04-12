<?php

declare(strict_types=1);

use Rector\DowngradePhp54\Rector\MethodCall\DowngradeInstanceMethodCallRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeInstanceMethodCallRector::class);
};
