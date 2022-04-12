<?php

declare(strict_types=1);

use Rector\DowngradePhp55\Rector\Foreach_\DowngradeForeachListRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeForeachListRector::class);
};
