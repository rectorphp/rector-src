<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\StmtsAwareInterface;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use Rector\BetterPhpDocParser\Comment\CommentsMerger;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\NodeAnalyzer\VariableAnalyzer;
use Rector\Core\Rector\AbstractRector;
use Rector\DeadCode\NodeAnalyzer\ExprUsedInNextNodeAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustVariableAssignRector\RemoveJustVariableAssignRectorTest
 */
final class RemoveJustVariableAssignRector extends AbstractRector
{
    public function __construct(
        private readonly VariableAnalyzer $variableAnalyzer,
        private readonly ExprUsedInNextNodeAnalyzer $exprUsedInNextNodeAnalyzer,
        private readonly CommentsMerger $commentsMerger
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove variable just to assign value or return value', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run()
    {
        $result = 100;

        $this->temporaryValue = $result;
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run()
    {
        $this->temporaryValue = 100;
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StmtsAwareInterface::class];
    }

    /**
     * @param StmtsAwareInterface $node
     */
    public function refactor(Node $node): ?Node
    {
        $stmts = (array) $node->stmts;
        if ($stmts === []) {
            return null;
        }

        $originalStmts = $stmts;

        foreach ($stmts as $key => $stmt) {
            $nextStmt = $stmts[$key + 1] ?? null;
            if (! $nextStmt instanceof Stmt) {
                continue;
            }

            $currentAssign = $this->matchExpressionAssign($stmt);
            if (! $currentAssign instanceof Assign) {
                continue;
            }

            $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($stmt);
            if ($phpDocInfo->getVarTagValueNode() instanceof VarTagValueNode) {
                continue;
            }

            $nextAssign = $this->matchExpressionAssign($nextStmt);
            if (! $nextAssign instanceof Assign) {
                continue;
            }

            if (! $this->areTwoVariablesCrossAssign($currentAssign, $nextAssign)) {
                continue;
            }

            if ($this->areTooComplexAssignsToShorten($currentAssign)) {
                continue;
            }

            // ...
            $currentAssign->var = $nextAssign->var;
            $this->commentsMerger->keepComments($stmt, [$stmts[$key + 1]]);

            unset($stmts[$key + 1]);
        }

        if ($originalStmts === $stmts) {
            return null;
        }

        $node->stmts = $stmts;

        return $node;
    }

    /**
     * This detects if two variables are cross assigned:
     *
     * $<some> = 1000;
     * $this->value = $<some>;
     *
     * + not used $<some> bellow, so removal will not break it
     */
    private function areTwoVariablesCrossAssign(Assign $currentAssign, Assign $nextAssign): bool
    {
        // is just re-assign to variable
        if (! $currentAssign->var instanceof Variable) {
            return false;
        }

        if (! $nextAssign->expr instanceof Variable) {
            return false;
        }

        if (! $this->nodeComparator->areNodesEqual($currentAssign->var, $nextAssign->expr)) {
            return false;
        }

        if ($this->variableAnalyzer->isUsedByReference($currentAssign->var)) {
            return false;
        }

        if ($this->variableAnalyzer->isUsedByReference($nextAssign->expr)) {
            return false;
        }

        if ($this->variableAnalyzer->isStaticOrGlobal($currentAssign->var)) {
            return false;
        }

        if (! $nextAssign->var instanceof ArrayDimFetch) {
            return ! $this->exprUsedInNextNodeAnalyzer->isUsed($nextAssign->expr);
        }

        if (! $this->shouldSkipArrayDimFetch($nextAssign->var, $currentAssign->var)) {
            return ! $this->exprUsedInNextNodeAnalyzer->isUsed($nextAssign->expr);
        }

        return false;
    }

    /**
     * Shortening should not make code less readable.
     */
    private function areTooComplexAssignsToShorten(Assign $currentAssign): bool
    {
        if ($currentAssign->expr instanceof Ternary) {
            return true;
        }

        return $currentAssign->expr instanceof Concat;
    }

    private function shouldSkipArrayDimFetch(ArrayDimFetch $arrayDimFetch, Variable $variable): bool
    {
        if ($arrayDimFetch->var instanceof ArrayDimFetch) {
            return $this->shouldSkipArrayDimFetch($arrayDimFetch->var, $variable);
        }

        if (! $arrayDimFetch->dim instanceof Expr) {
            return false;
        }

        return (bool) $this->betterNodeFinder->findFirst(
            $arrayDimFetch->dim,
            fn (Node $subNode): bool => $this->nodeComparator->areNodesEqual($variable, $subNode)
        );
    }

    private function matchExpressionAssign(Stmt $stmt): ?Assign
    {
        if (! $stmt instanceof Expression) {
            return null;
        }

        if (! $stmt->expr instanceof Assign) {
            return null;
        }

        return $stmt->expr;
    }
}
