<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\AttributeAndArgValueRefresh\Source;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class SetArrayArgValueRector extends AbstractRector
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
        return [Arg::class];
    }

    /**
     * @param Arg $node
     */
    public function refactor(Node $node): Arg
    {
        $node->value = new Array_([new ArrayItem(new String_('value'))]);

        return $node;
    }
}