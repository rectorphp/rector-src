<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DogFood\Rector\Closure\UpgradeRectorConfigRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(UpgradeRectorConfigRector::class);
};
