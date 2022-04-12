<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(CallableThisArrayToAnonymousFunctionRector::class);
};
