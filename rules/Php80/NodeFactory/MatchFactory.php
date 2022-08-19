<?php

declare(strict_types=1);

namespace Rector\Php80\NodeFactory;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Match_;
use PhpParser\Node\Expr\Throw_;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Throw_ as ThrowsStmt;
use Rector\Php80\Enum\MatchKind;
use Rector\Php80\NodeAnalyzer\MatchSwitchAnalyzer;
use Rector\Php80\ValueObject\CondAndExpr;

final class MatchFactory
{
    public function __construct(
        private readonly MatchArmsFactory $matchArmsFactory,
        private readonly MatchSwitchAnalyzer $matchSwitchAnalyzer
    ) {
    }

    /**
     * @param CondAndExpr[] $condAndExprs
     */
    public function createFromCondAndExprs(Expr $condExpr, array $condAndExprs, ?Stmt $nextStmt): ?Match_
    {
        $matchArms = $this->matchArmsFactory->createFromCondAndExprs($condAndExprs);
        $match = new Match_($condExpr, $matchArms);

        // implicit return default after switch
        if ($nextStmt instanceof Return_ && $nextStmt->expr instanceof Expr) {
            return $this->processImplicitReturnAfterSwitch($match, $condAndExprs, $nextStmt->expr);
        }

        if ($nextStmt instanceof ThrowsStmt) {
            return $this->processImplicitThrowsAfterSwitch($match, $condAndExprs, $nextStmt->expr);
        }

        return $match;
    }

    /**
     * @param CondAndExpr[] $condAndExprs
     */
    private function processImplicitReturnAfterSwitch(
        Match_ $match,
        array $condAndExprs,
        Expr $returnExpr
    ): ?Match_ {
//        if (! $nextStmt instanceof Return_) {
//            return $match;
//        }

//        $returnedExpr = $nextStmt->expr;
//        if (! $returnedExpr instanceof Expr) {
//            return $match;
//        }

        if ($this->matchSwitchAnalyzer->hasDefaultValue($match)) {
            return $match;
        }

        $assignVar = $this->resolveAssignVar($condAndExprs);
        if ($assignVar instanceof ArrayDimFetch) {
            return null;
        }

        if (! $assignVar instanceof Expr) {
            // @todo propagate somehow in value object return?
            // $this->removeNode($nextStmt);
        }

        $condAndExprs[] = new CondAndExpr([], $returnExpr, MatchKind::RETURN);
        return $this->createFromCondAndExprs($match->cond, $condAndExprs, null);
    }

    /**
     * @param CondAndExpr[] $condAndExprs
     */
    private function resolveAssignVar(array $condAndExprs): ?Expr
    {
        foreach ($condAndExprs as $condAndExpr) {
            $expr = $condAndExpr->getExpr();
            if (! $expr instanceof Assign) {
                continue;
            }

            return $expr->var;
        }

        return null;
    }

    /**
     * @param CondAndExpr[] $condAndExprs
     */
    private function processImplicitThrowsAfterSwitch(
        Match_ $match,
        array $condAndExprs,
        Expr $throwExpr
    ): ?Match_ {
        if ($this->matchSwitchAnalyzer->hasDefaultValue($match)) {
            return $match;
        }

        // @todo pass via value object
        // $this->removeNode($nextStmt);

        $throw = new Throw_($throwExpr);

        $condAndExprs[] = new CondAndExpr([], $throw, MatchKind::RETURN);
        return $this->createFromCondAndExprs($match->cond, $condAndExprs, null);
    }
}
