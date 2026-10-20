<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ClassReflectionRefresh\Source;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\TraitUse;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\RuleDefinition;

final class AddSomeTraitRector extends AbstractRector
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
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Class_
    {
        foreach ($node->getTraitUses() as $traitUse) {
            foreach ($traitUse->traits as $trait) {
                if ($this->isName($trait, SomeTrait::class)) {
                    return null;
                }
            }
        }

        $node->stmts = array_merge([new TraitUse([new FullyQualified(SomeTrait::class)])], $node->stmts);

        return $node;
    }
}
