<?php

declare(strict_types=1);

namespace Rector\Core\Php;

use Nette\Utils\Strings;
use Rector\Core\Configuration\Option;
use Rector\Core\Exception\Configuration\InvalidConfigurationException;
use Rector\Core\Php\PhpVersionResolver\ProjectComposerJsonPhpVersionResolver;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

/**
 * @see \Rector\Core\Tests\Php\PhpVersionProviderTest
 */
final class PhpVersionProvider
{
    /**
     * @var string
     * @see https://regex101.com/r/qBMnbl/1
     */
    private const VALID_PHP_VERSION_REGEX = '#^\d{5,6}$#';

    public function __construct(
        private ParameterProvider $parameterProvider,
        private ProjectComposerJsonPhpVersionResolver $projectComposerJsonPhpVersionResolver
    ) {
    }

    public function provide(): int
    {
        $phpVersionFeatures = $this->parameterProvider->provideParameter(Option::PHP_VERSION_FEATURES);
        $this->validatePhpVersionFeaturesParameter($phpVersionFeatures);

        if ($phpVersionFeatures > 0) {
            return $phpVersionFeatures;
        }

        // for tests
        if (StaticPHPUnitEnvironment::isPHPUnitRun()) {
            // so we don't have to keep with up with newest version
            return PhpVersion::PHP_10;
        }

        $projectComposerJson = getcwd() . '/composer.json';
        if (file_exists($projectComposerJson)) {
            $phpVersion = $this->projectComposerJsonPhpVersionResolver->resolve($projectComposerJson);
            if ($phpVersion !== null) {
                return $phpVersion;
            }
        }

        return PHP_VERSION_ID;
    }

    public function isAtLeastPhpVersion(int $phpVersion): bool
    {
        return $phpVersion <= $this->provide();
    }

    private function validatePhpVersionFeaturesParameter(mixed $phpVersionFeatures): void
    {
        if ($phpVersionFeatures === null) {
            return;
        }

        if (PhpVersion::isValid($phpVersionFeatures)) {
            return;
        }

        if (! is_int($phpVersionFeatures)) {
            $this->throwInvalidTypeException($phpVersionFeatures);
        }

        if (Strings::match(
            (string) $phpVersionFeatures,
            self::VALID_PHP_VERSION_REGEX
        ) && $phpVersionFeatures >= (PhpVersion::PHP_53 - 1)) {
            return;
        }

        $this->throwInvalidTypeException($phpVersionFeatures);
    }

    private function throwInvalidTypeException(mixed $phpVersionFeatures): void
    {
        $errorMessage = sprintf(
            'Parameter "%s::%s" must be int, "%s" given.%sUse constant from "%s" to provide it, e.g. "%s::%s"',
            Option::class,
            'PHP_VERSION_FEATURES',
            (string) $phpVersionFeatures,
            PHP_EOL,
            PhpVersion::class,
            PhpVersion::class,
            'PHP_80'
        );
        throw new InvalidConfigurationException($errorMessage);
    }
}
