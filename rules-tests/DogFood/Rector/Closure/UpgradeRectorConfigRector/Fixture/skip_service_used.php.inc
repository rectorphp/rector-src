<?php

namespace Rector\Tests\DogFood\Rector\Closure\UpgradeRectorConfigRector\Fixture;

use Rector\Config\RectorConfig;
use Rector\DogFood\Rector\Closure\UpgradeRectorConfigRector;

return static function (RectorConfig $rectorConfig): void {
    $services = $rectorConfig->services();

    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->set(UpgradeRectorConfigRector::class);
};
