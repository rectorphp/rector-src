<?php

declare(strict_types=1);

use Rector\DowngradePhp71\Rector\List_\DowngradeKeysInListRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeKeysInListRector::class);
};
