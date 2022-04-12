<?php

declare(strict_types=1);

use Rector\DowngradePhp71\Rector\ConstFetch\DowngradePhp71JsonConstRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradePhp71JsonConstRector::class);
};
