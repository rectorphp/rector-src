<?php

declare(strict_types=1);

namespace Rector\Tests\PhpParser\NodeTraverser\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Contract\Rector\OrderedRectorInterface;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\RuleDefinition;

/**
 * @see \Rector\Tests\Application\RectorRegistryTest
 */
final class OrderedClassRector extends AbstractRector implements OrderedRectorInterface
{
    public function precededRule(): string
    {
        return RuleUsingClassRector::class;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('This rule runs after RuleUsingClassRector', [new CodeSample('', '')]);
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
    public function refactor(Node $node): Node
    {
        return $node;
    }
}
