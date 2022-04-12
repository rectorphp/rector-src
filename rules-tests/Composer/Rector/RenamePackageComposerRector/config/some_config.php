<?php

declare(strict_types=1);

use Rector\Composer\Rector\RenamePackageComposerRector;
use Rector\Composer\ValueObject\RenamePackage;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RenamePackageComposerRector::class)
        ->configure([new RenamePackage('foo/bar', 'baz/bar'), new RenamePackage('foo/baz', 'baz/baz')]);
};
