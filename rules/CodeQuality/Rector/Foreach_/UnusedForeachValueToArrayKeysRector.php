<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Foreach_;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Foreach_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\Foreach_\UnusedForeachValueToArrayKeysRector\UnusedForeachValueToArrayKeysRectorTest
 */
final class UnusedForeachValueToArrayKeysRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change foreach with unused $value but only $key, to array_keys()',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $items = [];
        foreach ($values as $key => $value) {
            $items[$key] = null;
        }
    }
}
CODE_SAMPLE
,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $items = [];
        foreach (array_keys($values) as $key) {
            $items[$key] = null;
        }
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Foreach_::class];
    }

    /**
     * @param Foreach_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->keyVar === null) {
            return null;
        }

        // special case of nested array items
        if ($node->valueVar instanceof Array_) {
            $node->valueVar = $this->refactorArrayForeachValue($node->valueVar, $node);
            if ($node->valueVar->items !== []) {
                return null;
            }
        } elseif ($node->valueVar instanceof Variable) {
            if ($this->isVariableUsedInForeach($node->valueVar, $node)) {
                return null;
            }
        } else {
            return null;
        }

        if (is_a($this->getStaticType($node->expr), ObjectType::class)) {
            return null;
        }

        $this->removeForeachValueAndUseArrayKeys($node);

        return $node;
    }

    private function refactorArrayForeachValue(Array_ $array, Foreach_ $foreach): Array_
    {
        foreach ($array->items as $key => $arrayItem) {
            if (! $arrayItem instanceof ArrayItem) {
                continue;
            }

            $value = $arrayItem->value;
            if (! $value instanceof Variable) {
                return $array;
            }

            if ($this->isVariableUsedInForeach($value, $foreach)) {
                continue;
            }

            unset($array->items[$key]);
        }

        return $array;
    }

    private function isVariableUsedInForeach(Variable $variable, Foreach_ $foreach): bool
    {
        return (bool) $this->betterNodeFinder->findFirst(
            $foreach->stmts,
            fn (Node $node): bool => $this->nodeComparator->areNodesEqual($node, $variable)
        );
    }

    private function removeForeachValueAndUseArrayKeys(Foreach_ $foreach): void
    {
        // remove key value
        $foreach->valueVar = $foreach->keyVar;
        $foreach->keyVar = null;

        $foreach->expr = $this->nodeFactory->createFuncCall('array_keys', [$foreach->expr]);
    }
}
