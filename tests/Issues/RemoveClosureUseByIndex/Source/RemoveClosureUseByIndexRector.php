<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\RemoveClosureUseByIndex\Source;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class RemoveClosureUseByIndexRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('remove closure use with index 1', []);
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
        unset($node->uses[1]);
        return $node;
    }
}
