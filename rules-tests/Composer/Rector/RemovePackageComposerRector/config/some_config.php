<?php

declare(strict_types=1);

use Rector\Composer\Rector\RemovePackageComposerRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemovePackageComposerRector::class)
        ->configure(['vendor1/package3', 'vendor1/package1', 'vendor1/package2']);
};
