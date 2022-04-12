<?php

declare(strict_types=1);

use Rector\Composer\Rector\ReplacePackageAndVersionComposerRector;
use Rector\Composer\ValueObject\ReplacePackageAndVersion;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ReplacePackageAndVersionComposerRector::class)
        ->configure([new ReplacePackageAndVersion('vendor1/package1', 'vendor1/package3', '^4.0')]);
};
