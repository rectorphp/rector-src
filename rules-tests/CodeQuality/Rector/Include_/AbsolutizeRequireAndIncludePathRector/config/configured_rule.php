<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Include_\AbsolutizeRequireAndIncludePathRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(AbsolutizeRequireAndIncludePathRector::class);
};
