<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Identical\StrlenZeroToIdenticalEmptyStringRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(StrlenZeroToIdenticalEmptyStringRector::class);
};
