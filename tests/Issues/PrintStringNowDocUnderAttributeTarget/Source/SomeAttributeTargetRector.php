<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\PrintStringNowDocUnderAttributeTarget\Source;

use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\Name\FullyQualified;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class SomeAttributeTargetRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('', []);
    }

        /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Attribute::class];
    }

    /**
     * @param Attribute $node
     */
    public function refactor(Node $node): Node
    {
        $node->setAttribute('some_key', 'some_value');
        $node->name = new FullyQualified('SomeNewAttribute');

        return $node;
    }
}