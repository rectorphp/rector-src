<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Symfony\DependencyInjection\Rector\Class_\GetBySymfonyStringToConstructorInjectionRector;

return RectorConfig::configure()
    ->withRules([GetBySymfonyStringToConstructorInjectionRector::class]);
