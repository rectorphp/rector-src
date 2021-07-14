<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\Assign;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Include_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PHPStan\Type\VoidType;
use Rector\Core\NodeAnalyzer\CompactFuncCallAnalyzer;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\Assign\RemoveAssignOfVoidReturnFunctionRector\RemoveAssignOfVoidReturnFunctionRectorTest
 */
final class RemoveAssignOfVoidReturnFunctionRector extends AbstractRector
{
    public function __construct(
        private CompactFuncCallAnalyzer $compactFuncCallAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove assign of void function/method to variable',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $value = $this->getOne();
    }

    private function getOne(): void
    {
    }
}
CODE_SAMPLE
,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $this->getOne();
    }

    private function getOne(): void
    {
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
        return [Assign::class];
    }

    /**
     * @param Assign $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node->expr instanceof FuncCall && ! $node->expr instanceof MethodCall && ! $node->expr instanceof StaticCall) {
            return null;
        }

        $exprType = $this->nodeTypeResolver->resolve($node->expr);
        if (! $exprType instanceof VoidType) {
            return null;
        }

        if ($this->isUsedNext($node->var)) {
            return null;
        }

        return $node->expr;
    }

    private function isUsedNext(Expr $expr): bool
    {
        return (bool) $this->betterNodeFinder->findFirstNext($expr, function (Node $node) use ($expr): bool {
            if (! $node instanceof FuncCall) {
                if ($this->nodeComparator->areNodesEqual($expr, $node)) {
                    return true;
                }

                return $node instanceof Include_;
            }

            if ($expr instanceof Variable) {
                return $this->compactFuncCallAnalyzer->isInCompact($node, $expr);
            }

            return false;
        });
    }
}
