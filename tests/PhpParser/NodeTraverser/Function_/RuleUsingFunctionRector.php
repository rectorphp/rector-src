<?php

declare(strict_types=1);

namespace Rector\Tests\PhpParser\NodeTraverser\Function_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\RuleDefinition;

/**
 * @see \Rector\Tests\PhpParser\NodeTraverser\RectorNodeTraverserTest
 */
final class RuleUsingFunctionRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('This rule applies to functions', [new CodeSample('', '')]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Function_::class];
    }

    /**
     * @param Function_ $node
     */
    public function refactor(Node $node): Node
    {
        return $node;
    }
}
