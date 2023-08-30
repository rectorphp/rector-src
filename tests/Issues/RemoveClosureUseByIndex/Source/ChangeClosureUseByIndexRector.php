<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\RemoveClosureUseByIndex\Source;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class ChangeClosureUseByIndexRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('change closure use with index 1 to d', []);
    }

    public function getNodeTypes(): array
    {
        return [
            Closure::class,
        ];
    }

    /**
     * @param Closure $node
     */
    public function refactor(Node $node)
    {
        $node->uses[1]->var->name = 'd';
        return $node;
    }
}
