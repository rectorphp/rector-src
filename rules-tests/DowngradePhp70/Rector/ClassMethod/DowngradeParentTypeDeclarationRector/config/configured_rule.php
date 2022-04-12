<?php

declare(strict_types=1);

use Rector\DowngradePhp70\Rector\ClassMethod\DowngradeParentTypeDeclarationRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeParentTypeDeclarationRector::class);
};
