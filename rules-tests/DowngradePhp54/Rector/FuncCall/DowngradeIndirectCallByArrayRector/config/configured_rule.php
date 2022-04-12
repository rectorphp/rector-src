<?php

declare(strict_types=1);

use Rector\DowngradePhp54\Rector\FuncCall\DowngradeIndirectCallByArrayRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeIndirectCallByArrayRector::class);
};
