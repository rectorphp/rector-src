<?php

declare(strict_types=1);

namespace Rector\Composer\Rector;

use Rector\Composer\Contract\Rector\ComposerRectorInterface;
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Composer\Rector\RemovePackageComposerRector\RemovePackageComposerRectorTest
 */
final class RemovePackageComposerRector implements ComposerRectorInterface
{
    /**
     * @deprecated
     * @var string
     */
    public const PACKAGE_NAMES = 'package_names';

    /**
     * @var string[]
     */
    private array $packageNames = [];

    public function refactor(ComposerJson $composerJson): void
    {
        foreach ($this->packageNames as $packageName) {
            $composerJson->removePackage($packageName);
        }
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove package from "require" and "require-dev" in `composer.json`', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
{
    "require": {
        "symfony/console": "^3.4"
    }
}
CODE_SAMPLE
            ,
                <<<'CODE_SAMPLE'
{
}
CODE_SAMPLE
            ,
                ['symfony/console']
            ),
        ]);
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $packagesNames = $configuration[self::PACKAGE_NAMES] ?? $configuration;

        Assert::isArray($packagesNames);
        Assert::allString($packagesNames);

        $this->packageNames = $packagesNames;
    }
}
