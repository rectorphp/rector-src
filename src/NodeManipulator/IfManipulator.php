<?php

declare(strict_types=1);

namespace Rector\Core\NodeManipulator;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\Exit_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\EarlyReturn\NodeTransformer\ConditionInverter;

final class IfManipulator
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly StmtsManipulator $stmtsManipulator,
        private readonly ValueResolver $valueResolver,
        private readonly ConditionInverter $conditionInverter,
        private readonly NodeComparator $nodeComparator
    ) {
    }

    /**
     * Matches:
     *
     * if (<$value> !== null) {
     *     return $value;
     * }
     */
    public function matchIfNotNullReturnValue(If_ $if): ?Expr
    {
        if (count($if->stmts) !== 1) {
            return null;
        }

        $insideIfNode = $if->stmts[0];
        if (! $insideIfNode instanceof Return_) {
            return null;
        }

        if (! $if->cond instanceof NotIdentical) {
            return null;
        }

        return $this->matchComparedAndReturnedNode($if->cond, $insideIfNode);
    }

    /**
     * @return If_[]
     */
    public function collectNestedIfsWithOnlyReturn(If_ $if): array
    {
        $ifs = [];

        $currentIf = $if;
        while ($this->isIfWithOnlyStmtIf($currentIf)) {
            $ifs[] = $currentIf;

            /** @var If_ $currentIf */
            $currentIf = $currentIf->stmts[0];
        }

        if ($ifs === []) {
            return [];
        }

        if (! $this->hasOnlyStmtOfType($currentIf, Return_::class)) {
            return [];
        }

        // last node is with the return value
        $ifs[] = $currentIf;

        return $ifs;
    }

    public function isIfAndElseWithSameVariableAssignAsLastStmts(If_ $if, Expr $desiredExpr): bool
    {
        if (! $if->else instanceof Else_) {
            return false;
        }

        if ((bool) $if->elseifs) {
            return false;
        }

        $lastIfStmt = $this->stmtsManipulator->getUnwrappedLastStmt($if->stmts);
        if (! $lastIfStmt instanceof Assign) {
            return false;
        }

        $lastElseStmt = $this->stmtsManipulator->getUnwrappedLastStmt($if->else->stmts);
        if (! $lastElseStmt instanceof Assign) {
            return false;
        }

        if (! $lastIfStmt->var instanceof Variable) {
            return false;
        }

        if (! $this->nodeComparator->areNodesEqual($lastIfStmt->var, $lastElseStmt->var)) {
            return false;
        }

        return $this->nodeComparator->areNodesEqual($desiredExpr, $lastElseStmt->var);
    }

    /**
     * @return If_[]
     */
    public function collectNestedIfsWithNonBreaking(Foreach_ $foreach): array
    {
        if (count($foreach->stmts) !== 1) {
            return [];
        }

        $onlyForeachStmt = $foreach->stmts[0];
        if (! $onlyForeachStmt instanceof If_) {
            return [];
        }

        $ifs = [];

        $currentIf = $onlyForeachStmt;
        while ($this->isIfWithOnlyStmtIf($currentIf)) {
            $ifs[] = $currentIf;

            /** @var If_ $currentIf */
            $currentIf = $currentIf->stmts[0];
        }

        // IfManipulator is not build to handle elseif and else
        if (! $this->isIfWithoutElseAndElseIfs($currentIf)) {
            return [];
        }

        $return = $this->betterNodeFinder->findFirstInstanceOf($currentIf->stmts, Return_::class);

        if ($return instanceof Return_) {
            return [];
        }

        $exit = $this->betterNodeFinder->findFirstInstanceOf($currentIf->stmts, Exit_::class);
        if ($exit instanceof Exit_) {
            return [];
        }

        // last node is with the expression

        $ifs[] = $currentIf;

        return $ifs;
    }

    /**
     * @param class-string<Stmt> $stmtClass
     */
    public function isIfWithOnly(Node $node, string $stmtClass): bool
    {
        if (! $node instanceof If_) {
            return false;
        }

        if (! $this->isIfWithoutElseAndElseIfs($node)) {
            return false;
        }

        return $this->hasOnlyStmtOfType($node, $stmtClass);
    }

    public function isIfWithOnlyOneStmt(If_ $if): bool
    {
        return count($if->stmts) === 1;
    }

    public function isIfWithoutElseAndElseIfs(If_ $if): bool
    {
        if ($if->else instanceof Else_) {
            return false;
        }

        return $if->elseifs === [];
    }

    public function createIfNegation(Expr $expr, Return_ $return): If_
    {
        $expr = $this->conditionInverter->createInvertedCondition($expr);
        return $this->createIfStmt($expr, $return);
    }

    public function createIfStmt(Expr $condExpr, Stmt $stmt): If_
    {
        return new If_($condExpr, [
            'stmts' => [$stmt],
        ]);
    }

    private function matchComparedAndReturnedNode(NotIdentical $notIdentical, Return_ $return): ?Expr
    {
        if ($this->nodeComparator->areNodesEqual(
            $notIdentical->left,
            $return->expr
        ) && $this->valueResolver->isNull($notIdentical->right)) {
            return $notIdentical->left;
        }

        if (! $this->nodeComparator->areNodesEqual($notIdentical->right, $return->expr)) {
            return null;
        }

        if ($this->valueResolver->isNull($notIdentical->left)) {
            return $notIdentical->right;
        }

        return null;
    }

    private function isIfWithOnlyStmtIf(If_ $if): bool
    {
        if (! $this->isIfWithoutElseAndElseIfs($if)) {
            return false;
        }

        return $this->hasOnlyStmtOfType($if, If_::class);
    }

    /**
     * @param class-string<Stmt> $stmtClass
     */
    private function hasOnlyStmtOfType(If_ $if, string $stmtClass): bool
    {
        $stmts = $if->stmts;
        if (count($stmts) !== 1) {
            return false;
        }

        return $stmts[0] instanceof $stmtClass;
    }
}
