<?php

// @see https://github.com/composer-unused/composer-unused

declare(strict_types=1);

use ComposerUnused\ComposerUnused\Configuration\Configuration;
use ComposerUnused\ComposerUnused\Configuration\PatternFilter;

return static function (Configuration $config): Configuration {
    // rector dependencies
    $config->addPatternFilter(PatternFilter::fromString('#rector/.#'));
    $config->addPatternFilter(PatternFilter::fromString('#phpstan/phpstan#'));
    $config->addPatternFilter(PatternFilter::fromString('#symfony/polyfill-php81#'));

    return $config;
};
