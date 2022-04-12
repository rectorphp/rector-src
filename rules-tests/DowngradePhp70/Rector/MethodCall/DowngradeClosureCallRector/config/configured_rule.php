<?php

declare(strict_types=1);

use Rector\DowngradePhp70\Rector\MethodCall\DowngradeClosureCallRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeClosureCallRector::class);
};
