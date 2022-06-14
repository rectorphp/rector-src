<?php

declare(strict_types=1);

namespace Utils\Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Utils\Rector\Tests\Rector\RenameSimpleRectorTest
 */
final class RenameSimpleRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Variable::class];
    }

    /**
     * @param Variable $node
     */
    public function refactor(Node $node): ?Node
    {
        $node->name = 'newValue';
        return $node;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        // needed only for simple test only
    }
}
