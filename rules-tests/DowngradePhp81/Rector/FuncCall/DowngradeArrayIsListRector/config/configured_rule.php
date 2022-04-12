<?php

declare(strict_types=1);

use Rector\DowngradePhp81\Rector\FuncCall\DowngradeArrayIsListRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeArrayIsListRector::class);
};
