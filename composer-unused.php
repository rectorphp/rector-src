<?php

// @see https://github.com/composer-unused/composer-unused

declare(strict_types=1);

use ComposerUnused\ComposerUnused\Configuration\Configuration;
use ComposerUnused\ComposerUnused\Configuration\PatternFilter;

return static function (Configuration $configuration): Configuration {
    // rector dependencies
    $configuration->addPatternFilter(PatternFilter::fromString('#rector/.#'));
    $configuration->addPatternFilter(PatternFilter::fromString('#phpstan/phpstan#'));

    return $configuration;
};
