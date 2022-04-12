<?php

declare(strict_types=1);

use Rector\DowngradePhp74\Rector\FuncCall\DowngradeStripTagsCallWithArrayRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeStripTagsCallWithArrayRector::class);
};
