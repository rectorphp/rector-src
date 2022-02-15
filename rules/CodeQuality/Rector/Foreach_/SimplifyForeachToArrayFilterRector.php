<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Foreach_;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\If_;
use Rector\CodeQuality\NodeFactory\ArrayFilterFactory;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\Foreach_\SimplifyForeachToArrayFilterRector\SimplifyForeachToArrayFilterRectorTest
 */
final class SimplifyForeachToArrayFilterRector extends AbstractRector
{
    public function __construct(
        private ArrayFilterFactory $arrayFilterFactory,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Simplify foreach with function filtering to array filter',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$directories = [];
$possibleDirectories = [];
foreach ($possibleDirectories as $possibleDirectory) {
    if (file_exists($possibleDirectory)) {
        $directories[] = $possibleDirectory;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$possibleDirectories = [];
$directories = array_filter($possibleDirectories, 'file_exists');
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
        if ($this->shouldSkip($node)) {
            return null;
        }

        /** @var If_ $ifNode */
        $ifNode = $node->stmts[0];

        $condExpr = $ifNode->cond;

        if ($condExpr instanceof FuncCall) {
            return $this->refactorFuncCall($ifNode, $condExpr, $node);
        }

        $foreachValueVar = $node->valueVar;
        if (! $foreachValueVar instanceof Variable) {
            return null;
        }

        $onlyStmt = $ifNode->stmts[0];

        if ($onlyStmt instanceof Expression) {
            if ($onlyStmt->expr instanceof Assign) {
                $assign = $onlyStmt->expr;

                // only the array dim fetch with key is allowed
                if (! $assign->var instanceof ArrayDimFetch) {
                    return null;
                }

                if (! $this->nodeComparator->areNodesEqual($foreachValueVar, $assign->expr)) {
                    return null;
                }

                return $this->createArrayDimFetchWithClosure($assign->var, $foreachValueVar, $condExpr, $node);
            }
        }

        // another condition - not supported yet
        return null;
    }

    private function shouldSkip(Foreach_ $foreach): bool
    {
        if (count($foreach->stmts) !== 1) {
            return true;
        }

        if (! $foreach->stmts[0] instanceof If_) {
            return true;
        }

        /** @var If_ $ifNode */
        $ifNode = $foreach->stmts[0];

        if ($ifNode->else !== null) {
            return true;
        }

        return $ifNode->elseifs !== [];
    }

    private function isArrayDimFetchInForLoop(Foreach_ $foreach, ArrayDimFetch $arrayDimFetch): bool
    {
        $loopVar = $foreach->expr;
        if (! $loopVar instanceof Variable) {
            return false;
        }

        $varThatIsModified = $arrayDimFetch->var;
        if (! $varThatIsModified instanceof Variable) {
            return false;
        }

        return $loopVar->name !== $varThatIsModified->name;
    }

    private function isSimpleCall(FuncCall $funcCall, Foreach_ $foreach): bool
    {
        if (count($funcCall->args) !== 1) {
            return false;
        }

        return $this->nodeComparator->areNodesEqual($funcCall->args[0], $foreach->valueVar);
    }

    private function refactorFuncCall(If_ $if, FuncCall $funcCall, Foreach_ $foreach): ?Assign
    {
        if (count($if->stmts) !== 1) {
            return null;
        }

        if (! $this->isSimpleCall($funcCall, $foreach)) {
            return null;
        }

        if (! $if->stmts[0] instanceof Expression) {
            return null;
        }

        $onlyNodeInIf = $if->stmts[0]->expr;
        if (! $onlyNodeInIf instanceof Assign) {
            return null;
        }

        $arrayDimFetch = $onlyNodeInIf->var;
        if (! $arrayDimFetch instanceof ArrayDimFetch) {
            return null;
        }

        if (! $this->nodeComparator->areNodesEqual($onlyNodeInIf->expr, $foreach->valueVar)) {
            return null;
        }

        $funcName = $this->getName($funcCall);
        if ($funcName === null) {
            return null;
        }

        if (! $this->isArrayDimFetchInForLoop($foreach, $arrayDimFetch)) {
            return null;
        }

        return $this->arrayFilterFactory->createSimpleFuncCallAssign($foreach, $funcName, $arrayDimFetch);
    }

    private function createArrayDimFetchWithClosure(
        ArrayDimFetch $arrayDimFetch,
        Variable $valueVariable,
        Expr $condExpr,
        Foreach_ $foreach
    ): Assign {
        $filterFunction = new Node\Expr\ArrowFunction([
            'params' => [new Node\Param($valueVariable)],
            'expr' => $condExpr,
        ]);
        $args = [new Arg($foreach->expr), new Arg($filterFunction)];

        $arrayFilterFuncCall = new FuncCall(new Name('array_filter'), $args);
        return new Assign($arrayDimFetch->var, $arrayFilterFuncCall);
    }
}
