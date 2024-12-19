<?php

declare(strict_types=1);

namespace Rector\NodeManipulator;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Finally_;
use PhpParser\Node\Stmt\TryCatch;
use Rector\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\DeadCode\NodeAnalyzer\ExprUsedInNodeAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\PhpParser\Comparing\NodeComparator;
use Rector\PhpParser\Node\BetterNodeFinder;

final readonly class StmtsManipulator
{
    public function __construct(
        private SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private BetterNodeFinder $betterNodeFinder,
        private NodeComparator $nodeComparator,
        private ExprUsedInNodeAnalyzer $exprUsedInNodeAnalyzer
    ) {
    }

    /**
     * @param Stmt[] $stmts
     */
    public function getUnwrappedLastStmt(array $stmts): null|Expr|Stmt
    {
        if ($stmts === []) {
            return null;
        }

        $lastStmtKey = array_key_last($stmts);
        $lastStmt = $stmts[$lastStmtKey];

        if ($lastStmt instanceof Expression) {
            $lastStmt->expr->setAttribute(AttributeKey::COMMENTS, $lastStmt->getAttribute(AttributeKey::COMMENTS));
            return $lastStmt->expr;
        }

        return $lastStmt;
    }

    /**
     * @param Stmt[] $stmts
     * @return Stmt[]
     */
    public function filterOutExistingStmts(ClassMethod $classMethod, array $stmts): array
    {
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
            (array) $classMethod->stmts,
            function (Node $node) use (&$stmts): null {
                foreach ($stmts as $key => $assign) {
                    if (! $this->nodeComparator->areNodesEqual($node, $assign)) {
                        continue;
                    }

                    unset($stmts[$key]);
                }

                return null;
            }
        );

        return $stmts;
    }

    public function isVariableUsedInNextStmt(
        StmtsAwareInterface $stmtsAware,
        int $jumpToKey,
        string $variableName
    ): bool {
        if ($stmtsAware->stmts === null) {
            return false;
        }

        $lastKey = array_key_last($stmtsAware->stmts);
        $stmts = [];

        for ($key = $jumpToKey; $key <= $lastKey; ++$key) {
            if (! isset($stmtsAware->stmts[$key])) {
                // can be just removed
                continue;
            }

            $stmts[] = $stmtsAware->stmts[$key];
        }

        if ($stmtsAware instanceof TryCatch) {
            $stmts = array_merge($stmts, $stmtsAware->catches);

            if ($stmtsAware->finally instanceof Finally_) {
                $stmts = array_merge($stmts, $stmtsAware->finally->stmts);
            }
        }

        $variable = new Variable($variableName);
        return (bool) $this->betterNodeFinder->findFirst(
            $stmts,
            fn (Node $subNode): bool => $this->exprUsedInNodeAnalyzer->isUsed($subNode, $variable)
        );
    }
}
