<?php

declare(strict_types=1);

use PHPStan\PharAutoloader;
use PHPStan\Turbo\TurboExtensionEnabler;

// PHPStan Turbo replaces php-parser classes, so it is activated before preload.php declares them
if (extension_loaded('phpstan_turbo')) {
    require_once __DIR__ . '/../vendor/autoload.php';
    TurboExtensionEnabler::activateIfCompatible(
        dirname((string) new ReflectionClass(PharAutoloader::class)->getFileName()) . '/phpstan.phar'
    );
}

// make local php-parser a priority to avoid conflict
require_once __DIR__ . '/../preload.php';
require_once __DIR__ . '/../vendor/autoload.php';

// silent deprecations, since we test them
error_reporting(E_ALL ^ E_DEPRECATED);

// performance boost
gc_disable();
