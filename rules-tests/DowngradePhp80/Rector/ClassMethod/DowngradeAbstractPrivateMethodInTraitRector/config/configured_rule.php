<?php

declare(strict_types=1);

use Rector\DowngradePhp80\Rector\ClassMethod\DowngradeAbstractPrivateMethodInTraitRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeAbstractPrivateMethodInTraitRector::class);
};
