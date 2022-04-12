<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\DowngradePhp55\Rector\ClassConstFetch\DowngradeClassConstantToStringRector;
use Rector\DowngradePhp55\Rector\Foreach_\DowngradeForeachListRector;
use Rector\DowngradePhp55\Rector\FuncCall\DowngradeBoolvalRector;
use Rector\DowngradePhp55\Rector\Isset_\DowngradeArbitraryExpressionArgsToEmptyAndIssetRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_54);

    $services = $containerConfigurator->services();
    $services->set(DowngradeClassConstantToStringRector::class);
    $services->set(DowngradeForeachListRector::class);
    $services->set(DowngradeBoolvalRector::class);
    $services->set(DowngradeArbitraryExpressionArgsToEmptyAndIssetRector::class);
};
