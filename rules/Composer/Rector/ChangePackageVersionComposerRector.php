<?php

declare(strict_types=1);

namespace Rector\Composer\Rector;

use Rector\Composer\Contract\Rector\ComposerRectorInterface;
use Rector\Composer\Guard\VersionGuard;
use Rector\Composer\ValueObject\PackageAndVersion;
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Composer\Rector\ChangePackageVersionComposerRector\ChangePackageVersionComposerRectorTest
 */
final class ChangePackageVersionComposerRector implements ComposerRectorInterface
{
    /**
     * @deprecated
     * @var string
     */
    public const PACKAGES_AND_VERSIONS = 'packages_and_versions';

    /**
     * @var PackageAndVersion[]
     */
    private array $packagesAndVersions = [];

    public function __construct(
        private readonly VersionGuard $versionGuard
    ) {
    }

    public function refactor(ComposerJson $composerJson): void
    {
        foreach ($this->packagesAndVersions as $packageAndVersion) {
            $composerJson->changePackageVersion(
                $packageAndVersion->getPackageName(),
                $packageAndVersion->getVersion()
            );
        }
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change package version `composer.json`', [new ConfiguredCodeSample(
            <<<'CODE_SAMPLE'
{
    "require-dev": {
        "symfony/console": "^3.4"
    }
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
{
    "require": {
        "symfony/console": "^4.4"
    }
}
CODE_SAMPLE
            ,
            [new PackageAndVersion('symfony/console', '^4.4')]
        ),
        ]);
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $packagesAndVersions = $configuration[self::PACKAGES_AND_VERSIONS] ?? $configuration;
        Assert::allIsAOf($packagesAndVersions, PackageAndVersion::class);

        $this->versionGuard->validate($packagesAndVersions);
        $this->packagesAndVersions = $packagesAndVersions;
    }
}
