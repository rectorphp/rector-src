<?php

declare(strict_types=1);

namespace Rector\Tests\VersionBonding\Fixture;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersion;
use Rector\ValueObject\PolyfillPackage;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Rector\VersionBonding\Contract\RelatedPolyfillInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class PolyfillPhp83Rector extends AbstractRector implements MinPhpVersionInterface, RelatedPolyfillInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Test rector bound to PHP 8.3 polyfill', []);
    }

    public function getNodeTypes(): array
    {
        return [Node\Stmt\Class_::class];
    }

    public function refactor(Node $node): ?Node
    {
        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersion::PHP_83;
    }

    public function providePolyfillPackage(): string
    {
        return PolyfillPackage::PHP_83;
    }
}
