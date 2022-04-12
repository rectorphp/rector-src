<?php

declare(strict_types=1);

use Rector\PHPUnit\Rector\MethodCall\AssertComparisonToSpecificMethodRector;
use Rector\PHPUnit\Rector\MethodCall\AssertFalseStrposToContainsRector;
use Rector\PHPUnit\Rector\MethodCall\AssertSameBoolNullToSpecificMethodRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(AssertComparisonToSpecificMethodRector::class);
    $services->set(AssertSameBoolNullToSpecificMethodRector::class);
    $services->set(AssertFalseStrposToContainsRector::class);
};
