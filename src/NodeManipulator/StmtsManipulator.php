<?php

declare(strict_types=1);

namespace Rector\NodeManipulator;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use Rector\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\PhpParser\Comparing\NodeComparator;
use Rector\PhpParser\Node\BetterNodeFinder;

final readonly class StmtsManipulator
{
    public function __construct(
        private SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private BetterNodeFinder $betterNodeFinder,
        private NodeComparator $nodeComparator
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

        $stmts = array_slice($stmtsAware->stmts, $jumpToKey, null, true);
        return (bool) $this->betterNodeFinder->findVariableOfName($stmts, $variableName);
    }
}
