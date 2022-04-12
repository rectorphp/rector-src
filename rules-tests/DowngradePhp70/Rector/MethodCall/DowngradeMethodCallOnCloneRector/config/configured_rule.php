<?php

declare(strict_types=1);

use Rector\DowngradePhp70\Rector\MethodCall\DowngradeMethodCallOnCloneRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeMethodCallOnCloneRector::class);
};
