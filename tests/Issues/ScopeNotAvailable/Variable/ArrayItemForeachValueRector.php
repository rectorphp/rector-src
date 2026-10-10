<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ScopeNotAvailable\Variable;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;
use Rector\Doc\CodeSample\CodeSample;
use Rector\Doc\RuleDefinition;
use Rector\Rector\AbstractRector;

final class ArrayItemForeachValueRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Hello!', [new CodeSample('', '')]);
    }

    /**
     * @return array<class-string<Expr>>
     */
    public function getNodeTypes(): array
    {
        return [Variable::class];
    }

    /**
     * @param Variable $node
     */
    public function refactor(Node $node): Node
    {
        return $node;
    }
}
