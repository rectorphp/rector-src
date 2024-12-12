<?php

declare(strict_types=1);

namespace Rector\Php\PhpVersionResolver;

use Composer\Semver\VersionParser;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Exception\Configuration\InvalidConfigurationException;
use Rector\FileSystem\JsonFileSystem;
use Rector\Php\PhpVersionProvider;
use Rector\Util\PhpVersionFactory;
use Rector\ValueObject\PhpVersion;

/**
 * @see \Rector\Tests\Php\PhpVersionResolver\ComposerJsonPhpVersionResolver\ComposerJsonPhpVersionResolverTest
 */
final class ComposerJsonPhpVersionResolver
{
    /**
     * @var array<string, PhpVersion::*|null>
     */
    private static array $cachedPhpVersions = [];

    /**
     * @return PhpVersion::*
     */
    public static function resolveFromCwdOrFail(): int
    {
        // read from composer.json PHP version as priority
        $projectComposerJsonFilePath = getcwd() . '/composer.json';
        if (file_exists($projectComposerJsonFilePath)) {
            $projectPhpVersion = self::resolve($projectComposerJsonFilePath);
            if (is_int($projectPhpVersion)) {
                return $projectPhpVersion;
            }
        }

        throw new InvalidConfigurationException(sprintf(
            'We could not find local "composer.json" or php version feature config to determine your PHP version.%sPlease, fill the PHP version set in withPhpSets() manually.',
            PHP_EOL
        ));
    }

    /**
     * @return PhpVersion::*|null
     */
    public static function resolve(string $composerJson): ?int
    {
        if (array_key_exists($composerJson, self::$cachedPhpVersions)) {
            return self::$cachedPhpVersions[$composerJson];
        }

        $projectComposerJson = JsonFileSystem::readFilePath($composerJson);

        // give this one a priority, as more generic one. see https://github.com/composer/composer/issues/7914
        $requirePhpVersion = $projectComposerJson['require']['php'] ?? $projectComposerJson['require']['php-64bit'] ?? null;
        if ($requirePhpVersion !== null) {
            self::$cachedPhpVersions[$composerJson] = self::createIntVersionFromComposerVersion($requirePhpVersion);
            return self::$cachedPhpVersions[$composerJson];
        }

        // see https://getcomposer.org/doc/06-config.md#platform
        $platformPhp = $projectComposerJson['config']['platform']['php'] ?? null;
        if ($platformPhp !== null) {
            self::$cachedPhpVersions[$composerJson] = PhpVersionFactory::createIntVersion($platformPhp);
            return self::$cachedPhpVersions[$composerJson];
        }

        // fallback from php version features defined
        // check hasParameter() here is essential to ensure only fallback when PHP_VERSION_FEATURES constant exists
        if (SimpleParameterProvider::hasParameter(Option::PHP_VERSION_FEATURES)) {
            return self::$cachedPhpVersions[$composerJson] = PhpVersionProvider::provideWithoutComposerJon();
        }

        return self::$cachedPhpVersions[$composerJson] = null;
    }

    /**
     * @return PhpVersion::*
     */
    private static function createIntVersionFromComposerVersion(string $projectPhpVersion): int
    {
        $versionParser = new VersionParser();
        $constraint = $versionParser->parseConstraints($projectPhpVersion);

        $lowerBound = $constraint->getLowerBound();
        $lowerBoundVersion = $lowerBound->getVersion();

        return PhpVersionFactory::createIntVersion($lowerBoundVersion);
    }
}
