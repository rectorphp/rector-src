<?php

declare(strict_types=1);

use Rector\Transform\Rector\String_\ToStringToMethodCallRector;
use Symfony\Component\Config\ConfigCache;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ToStringToMethodCallRector::class)
        ->configure([
            ConfigCache::class => 'getPath',
        ]);
};
