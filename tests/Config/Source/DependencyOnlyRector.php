<?php

declare(strict_types=1);

namespace Rector\Tests\Config\Source;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Never registered. It exists only as a constructor dependency of @see RegisteringRector,
 * which is enough for the container to build and cache it.
 */
final class DependencyOnlyRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('', []);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [String_::class];
    }

    public function refactor(Node $node): ?Node
    {
        return null;
    }
}
