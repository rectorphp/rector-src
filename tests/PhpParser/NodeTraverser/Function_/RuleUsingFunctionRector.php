<?php

declare(strict_types=1);

namespace Rector\Tests\PhpParser\NodeTraverser\Function_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @extends AbstractRector<Function_>
 * @see \Rector\Tests\PhpParser\NodeTraverser\RectorNodeTraverserTest
 */
final class RuleUsingFunctionRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('This rule applies to functions', [new CodeSample('', '')]);
    }

    public function getNodeTypes(): array
    {
        return [Function_::class];
    }

    public function refactor(Node $node): Node
    {
        return $node;
    }
}
