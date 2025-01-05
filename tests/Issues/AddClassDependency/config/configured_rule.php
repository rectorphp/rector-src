<?php

declare(strict_types=1);

use Rector\Symfony\DependencyInjection\Rector\Class_\GetBySymfonyStringToConstructorInjectionRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        GetBySymfonyStringToConstructorInjectionRector::class,
    ]);
