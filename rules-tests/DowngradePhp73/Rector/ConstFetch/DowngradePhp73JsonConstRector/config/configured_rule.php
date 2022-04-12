<?php

declare(strict_types=1);

use Rector\DowngradePhp73\Rector\ConstFetch\DowngradePhp73JsonConstRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradePhp73JsonConstRector::class);
};
