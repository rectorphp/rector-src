<?php

declare(strict_types=1);

namespace Rector\Tests\PhpParser\NodeTraverser\ClassLike;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassLike;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\PhpParser\NodeTraverser\RectorNodeTraverserTest
 */
final class RuleUsingClassLikeRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('This rule applies to class like nodes', [new CodeSample('', '')]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassLike::class];
    }

    public function refactor(Node $node): Node
    {
        return $node;
    }
}
