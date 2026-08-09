<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @extends AbstractRector<FuncCall>
 * @see \Rector\Tests\CodeQuality\Rector\FuncCall\SimplifyFuncGetArgsCountRector\SimplifyFuncGetArgsCountRectorTest
 */
final class SimplifyFuncGetArgsCountRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Simplify count of func_get_args() to func_num_args()',
            [new CodeSample('count(func_get_args());', 'func_num_args();')]
        );
    }

    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node, 'count')) {
            return null;
        }

        if ($node->isFirstClassCallable()) {
            return null;
        }

        $firstArg = $node->getArgs()[0];

        if (! $firstArg->value instanceof FuncCall) {
            return null;
        }

        $innerFuncCall = $firstArg->value;

        if (! $this->isName($innerFuncCall, 'func_get_args')) {
            return null;
        }

        return $this->nodeFactory->createFuncCall('func_num_args');
    }
}
