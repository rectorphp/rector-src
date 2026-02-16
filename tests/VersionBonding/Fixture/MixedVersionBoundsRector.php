<?php

declare(strict_types=1);

namespace Rector\Tests\VersionBonding\Fixture;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Rector\VersionBonding\Contract\DeprecatedAtVersionInterface;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class MixedVersionBoundsRector extends AbstractRector implements MinPhpVersionInterface, DeprecatedAtVersionInterface
{
    public function __construct(
        private readonly int $minPhpVersion,
        private readonly int $deprecatedAtVersion,
    ) {}

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Test rector with deprecation-aware versions', []);
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
        return $this->minPhpVersion;
    }

    public function provideDeprecatedAtVersion(): int
    {
        return $this->deprecatedAtVersion;
    }
}
