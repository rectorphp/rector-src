<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once  __DIR__ . '/../target-repository/stubs-rector/PHPUnit/Framework/TestCase.php';

return RectorConfig::configure()
    ->withSkip(DowngradeRectorConfig::DEPENDENCY_EXCLUDE_PATHS)
    ->withPHPStanConfigs([__DIR__ . '/phpstan-for-downgrade.neon'])
    ->withDowngradeSets(php74: true);

/**
 * Configuration consts for the different rector.php config files
 */
final class DowngradeRectorConfig
{
    /**
     * Exclude paths when downgrading a dependency
     */
    public const DEPENDENCY_EXCLUDE_PATHS = [
        '*/tests/*',
        // symfony test are parts of package
        '*/Test/*',

        // Individual classes that can be excluded because
        // they are not used by Rector, and they use classes
        // loaded with "require-dev" so it'd throw an error
        // only for composer patches on composer install - not needed in final package
        'vendor/cweagans/*',
        'vendor/rector/rector-generator/templates',
    ];
}
