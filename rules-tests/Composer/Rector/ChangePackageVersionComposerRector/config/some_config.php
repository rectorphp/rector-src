<?php

declare(strict_types=1);

use Rector\Composer\Rector\ChangePackageVersionComposerRector;
use Rector\Composer\ValueObject\PackageAndVersion;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ChangePackageVersionComposerRector::class)
        ->configure([new PackageAndVersion('vendor1/package3', '^15.0')]);
};
