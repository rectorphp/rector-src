<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PSR4\Composer\PSR4NamespaceMatcher;
use Rector\PSR4\Contract\PSR4AutoloadNamespaceMatcherInterface;

return static function (RectorConfig $rectorConfig): void {
    $services = $rectorConfig->services();

    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    // psr-4
    $services->alias(PSR4AutoloadNamespaceMatcherInterface::class, PSR4NamespaceMatcher::class);

    $services->load('Rector\\', __DIR__ . '/../rules')
        ->exclude([
            __DIR__ . '/../rules/*/ValueObject/*',
            __DIR__ . '/../rules/*/Rector/*',
            __DIR__ . '/../rules/*/Contract/*',
            __DIR__ . '/../rules/*/Exception/*',
            __DIR__ . '/../rules/*/Enum/*',
            __DIR__ . '/../rules/DowngradePhp80/Reflection/SimplePhpParameterReflection.php',
        ]);
};
