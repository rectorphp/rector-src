<?php

namespace Rector\Tests\PhpParser\NodeTraverser\StopTraverseOnTypeChange\Class_;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RuleChangingClassToTraitRector extends AbstractRector
{

    public function getRuleDefinition(): RuleDefinition
    {
    }

    public function getNodeTypes(): array
    {
        return [Node\Stmt\Class_::class];
    }

    /**
     * @param Node\Stmt\Class_ $node
     * @return Node\Stmt\Trait_
     */
    public function refactor(Node $node)
    {
        return new Node\Stmt\Trait_('SomeTrait');
    }
}
