<?php

declare(strict_types=1);

use Rector\DowngradePhp72\Rector\FuncCall\DowngradeStreamIsattyRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeStreamIsattyRector::class);
};
