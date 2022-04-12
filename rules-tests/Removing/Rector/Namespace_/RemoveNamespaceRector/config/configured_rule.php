<?php

declare(strict_types=1);

use Rector\Removing\Rector\Namespace_\RemoveNamespaceRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveNamespaceRector::class)
        ->configure(['App']);
};
