<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ScopeNotAvailable\Variable;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @extends AbstractRector<Variable>
 */
final class ArrayItemForeachValueRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Hello!', [new CodeSample('', '')]);
    }

    public function getNodeTypes(): array
    {
        return [Variable::class];
    }

    public function refactor(Node $node): Node
    {
        return $node;
    }
}
