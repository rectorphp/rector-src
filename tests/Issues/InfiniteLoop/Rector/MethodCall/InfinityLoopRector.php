<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\InfiniteLoop\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\NodeTraverser;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Issues\InfiniteLoop\InfiniteLoopTest
 */
final class InfinityLoopRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Assign::class, MethodCall::class];
    }

    /**
     * @param Assign|MethodCall $node
     */
    public function refactor(Node $node): Assign|null|int
    {
        if ($node instanceof Assign) {
            return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
        }

        if (! $this->isName($node->name, 'modify')) {
            return null;
        }

        return new Assign($node->var, $node);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Road to left... to left... to lefthell..', []);
    }
}
