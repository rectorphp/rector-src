<?php declare(strict_types = 1);

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

$config = new Configuration();

return $config
    ->addPathToScan(__DIR__ . '/build/config', false)
    ->ignoreErrorsOnPackage('phpunit/phpunit', [ErrorType::DEV_DEPENDENCY_IN_PROD]) // prepared test tooling
    ->ignoreErrorsOnPaths([
        __DIR__ . '/stubs',
        __DIR__ . '/tests',
        __DIR__ . '/rules-tests',
    ], [ErrorType::UNKNOWN_CLASS]);
