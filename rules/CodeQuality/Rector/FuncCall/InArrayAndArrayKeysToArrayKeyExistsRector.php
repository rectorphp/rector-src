<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use Rector\Core\NodeAnalyzer\ArgsAnalyzer;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\FuncCall\InArrayAndArrayKeysToArrayKeyExistsRector\InArrayAndArrayKeysToArrayKeyExistsRectorTest
 */
final class InArrayAndArrayKeysToArrayKeyExistsRector extends AbstractRector
{
    public function __construct(private ArgsAnalyzer $argsAnalyzer)
    {
    }

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

        if (! $this->argsAnalyzer->isArgsInstanceInArgsPositions($node->args, [0, 1])) {
            return null;
        }

        /** @var Arg $secondArgument */
        $secondArgument = $node->args[1];
        if (! $secondArgument->value instanceof FuncCall) {
            return null;
        }

        if (! $this->isName($secondArgument->value, 'array_keys')) {
            return null;
        }

        if (count($secondArgument->value->args) > 1) {
            return null;
        }

        /** @var Arg $keyArg */
        $keyArg = $node->args[0];
        /** @var Arg $arrayArg */
        $arrayArg = $node->args[1];

        /** @var FuncCall $innerFuncCallNode */
        $innerFuncCallNode = $arrayArg->value;
        $arrayArg = $innerFuncCallNode->args[0];

        $node->name = new Name('array_key_exists');
        $node->args = [$keyArg, $arrayArg];

        return $node;
    }
}
