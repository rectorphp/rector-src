<?php

declare(strict_types=1);

use Rector\DowngradePhp80\Rector\StaticCall\DowngradePhpTokenRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradePhpTokenRector::class);
};
