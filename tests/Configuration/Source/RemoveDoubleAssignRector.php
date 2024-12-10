<?php

namespace Rector\Tests\Configuration\Source;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Dummy rector with same class name as an official one
 */
class RemoveDoubleAssignRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Test short rule name conflicts', []);
    }

    public function getNodeTypes(): array
    {
        return [];
    }

    public function refactor(Node $node): ?Node
    {
        return $node;
    }
}
