<?php

declare(strict_types=1);

namespace Rector\Tests\VersionBonding\Fixture;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Rector\VersionBonding\Contract\DeprecatedAtVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class DeprecatedAtVersionRector extends AbstractRector implements DeprecatedAtVersionInterface
{
    public function __construct(
        private readonly int $deprecatedAtVersion,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Test rector with deprecated-at PHP version', []);
    }

    public function getNodeTypes(): array
    {
        return [Node\Stmt\Class_::class];
    }

    public function refactor(Node $node): ?Node
    {
        return null;
    }

    public function provideDeprecatedAtVersion(): int
    {
        return $this->deprecatedAtVersion;
    }
}
