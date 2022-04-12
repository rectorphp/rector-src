<?php

declare(strict_types=1);

use Rector\DowngradePhp55\Rector\FuncCall\DowngradeBoolvalRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeBoolvalRector::class);
};
