<?php

declare(strict_types=1);

namespace Rector\Tests\PhpParser\NodeTraverser\StopTraverseOnTypeChange\Class_;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Trait_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RuleChangingClassToTraitRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change node from class to trait', []);
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): Trait_
    {
        $trait = new Trait_('SomeTrait');
        $trait->namespacedName = new Name('SomeNamespace\SomeTrait');

        return $trait;
    }
}
