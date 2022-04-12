<?php

declare(strict_types=1);

use Rector\DowngradePhp56\Rector\CallLike\DowngradeArgumentUnpackingRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeArgumentUnpackingRector::class);
};
