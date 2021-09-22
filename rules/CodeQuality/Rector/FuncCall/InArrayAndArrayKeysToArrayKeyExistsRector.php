<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\FuncCall\InArrayAndArrayKeysToArrayKeyExistsRector\InArrayAndArrayKeysToArrayKeyExistsRectorTest
 */
final class InArrayAndArrayKeysToArrayKeyExistsRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Simplify `in_array` and `array_keys` functions combination into `array_key_exists` when `array_keys` has one argument only',
            [new CodeSample('in_array("key", array_keys($array), true);', 'array_key_exists("key", $array);')]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node, 'in_array')) {
            return null;
        }

        $secondArgument = $node->getArgs()[1]->value;
        if (! $secondArgument instanceof FuncCall) {
            return null;
        }

        if (! $this->isName($secondArgument, 'array_keys')) {
            return null;
        }

        if (count($secondArgument->args) > 1) {
            return null;
        }

        $keyArg = $node->getArgs()[0];
        $arrayArg = $node->getArgs()[1];

        /** @var FuncCall $innerFuncCallNode */
        $innerFuncCallNode = $arrayArg->value;
        $arrayArg = $innerFuncCallNode->getArgs()[0];

        $node->name = new Name('array_key_exists');
        $node->args = [$keyArg, $arrayArg];

        return $node;
    }
}
