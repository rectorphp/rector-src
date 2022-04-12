<?php

declare(strict_types=1);

use Rector\DowngradePhp53\Rector\Dir\DirConstToFileConstRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DirConstToFileConstRector::class);
};
