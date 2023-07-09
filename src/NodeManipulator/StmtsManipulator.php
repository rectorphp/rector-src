<?php

declare(strict_types=1);

namespace Rector\Core\NodeManipulator;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;

final class StmtsManipulator
{
    public function __construct(
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly NodeComparator $nodeComparator
    ) {
    }

    /**
     * @param Stmt[] $stmts
     */
    public function getUnwrappedLastStmt(array $stmts): ?Node
    {
        $lastStmtKey = array_key_last($stmts);
        $lastStmt = $stmts[$lastStmtKey];

        if ($lastStmt instanceof Expression) {
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
            function (Node $node) use (&$stmts) {
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

        /**
     * @param Stmt[] $stmts
     * @return Stmt[]
     */
    public function isVariableUsedInNextStmt(StmtsAwareInterface $node, int $jumpToKey, string $variableName): bool
    {
        if ($node->stmts === null) {
            return false;
        }

        $totalKeys = array_key_last($node->stmts);
        for ($key = $jumpToKey; $key <= $totalKeys; ++$key) {
            if (! isset($node->stmts[$key])) {
                continue;
            }

            $isVariableUsed = (bool) $this->betterNodeFinder->findVariableOfName(
                $node->stmts[$key],
                $variableName
            );

            if ($isVariableUsed) {
                return true;
            }
        }

        return false;
    }
}
