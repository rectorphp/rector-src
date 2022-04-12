<?php

declare(strict_types=1);

use Rector\DowngradePhp72\Rector\ConstFetch\DowngradePhp72JsonConstRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradePhp72JsonConstRector::class);
};
