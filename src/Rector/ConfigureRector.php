<?php

declare(strict_types=1);

namespace Rector\Core\Rector;

use PhpParser\Node;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ConfigureRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
    }

    public function getNodeTypes(): array
    {
        return [Node\Expr\MethodCall::class];
    }

    /**
     * @param Node\Expr\MethodCall $node
     */
    public function refactor(Node $node)
    {
        if (! $this->isName($node->name, 'call')) {
            return null;
        }

        $firstArgValue = $node->args[0]->value;
        if (! $this->valueResolver->isValue($firstArgValue, 'configure')) {
            return null;
        }

        $node->name = new Node\Identifier('configure');

        $secondArg = $node->args[1]->value;
        if (! $secondArg instanceof Node\Expr\Array_) {
            return null;
        }

        // must be exactly single item
        if (count($secondArg->items) !== 1) {
            return null;
        }

        /** @var Node\Expr\ArrayItem $firstNestedItem */
        $firstNestedItem = $secondArg->items[0];

        if (! $firstNestedItem->value instanceof Node\Expr\Array_) {
            return null;
        }

        /** @var Node\Expr\ArrayItem $secondNestedItem */
        $secondNestedItem = $firstNestedItem->value->items[0];

        if ($secondNestedItem->value instanceof Node\Expr\StaticCall) {
            // unwrap inline
            $simpleArgumenstArray = $secondNestedItem->value->args[0]->value;
        } else {
            $simpleArgumenstArray = $secondNestedItem->value;
        }

        $node->args = [new Node\Arg($simpleArgumenstArray)];

        return $node;
    }
}
