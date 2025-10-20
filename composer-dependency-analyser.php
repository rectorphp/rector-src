<?php

declare(strict_types=1);

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

$config = new Configuration();

return $config
    ->addPathToScan(__DIR__ . '/build/config', false)
    ->addPathToScan('bin', false)
    // prepared test tooling
    ->ignoreErrorsOnPackage('phpunit/phpunit', [ErrorType::DEV_DEPENDENCY_IN_PROD])
    // pinned v3.x version
    ->ignoreErrorsOnPackage('react/promise', [ErrorType::UNUSED_DEPENDENCY])
    // ensure use version ^3.2.0
    ->ignoreErrorsOnPackage('composer/pcre', [ErrorType::UNUSED_DEPENDENCY])

    ->ignoreErrorsOnPaths([
        __DIR__ . '/stubs',
        __DIR__ . '/tests',
        __DIR__ . '/rules-tests',
    ], [ErrorType::UNKNOWN_CLASS])

    ->disableExtensionsAnalysis();
