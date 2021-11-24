<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\If_;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\ValueObject\Application\File;
use Rector\EarlyReturn\Rector\If_\RemoveAlwaysElseRector;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class ExprUsedInNextNodeAnalyzer
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private ExprUsedInNodeAnalyzer $exprUsedInNodeAnalyzer
    ) {
    }

    public function isUsed(Expr $expr, File $file): bool
    {
        $parentStmt = $this->betterNodeFinder->findParentType($expr, Stmt::class);

        if ($parentStmt instanceof Stmt) {
            $statementDepth = $parentStmt->getAttribute(AttributeKey::STATEMENT_DEPTH);
            if ($statementDepth === 0) {
                return $this->isUsedInRootStmts($file, $expr);
            }
        }

        return (bool) $this->betterNodeFinder->findFirstNext(
            $expr,
            function (Node $node) use ($expr): bool {
                if (! $node instanceof If_) {
                    return $this->exprUsedInNodeAnalyzer->isUsed($node, $expr);
                }

                /**
                 * handle when used along with RemoveAlwaysElseRector
                 */
                if (! $this->hasIfChangedByRemoveAlwaysElseRector($node)) {
                    return $this->exprUsedInNodeAnalyzer->isUsed($node, $expr);
                }

                return true;
            }
        );
    }

    private function hasIfChangedByRemoveAlwaysElseRector(If_ $if): bool
    {
        $createdByRule = $if->getAttribute(AttributeKey::CREATED_BY_RULE);
        return $createdByRule === RemoveAlwaysElseRector::class;
    }

    private function isUsedInRootStmts(File $file, Expr $expr): bool
    {
        // topmost file without namespace, look for file nodes
        $seekNode = $file->getOldStmts();

        $usedFoundNode = $this->betterNodeFinder->findFirst($seekNode, function (\PhpParser\Node $node) use (
            $expr
        ): bool {
            if ($node->getEndTokenPos() < $expr->getStartTokenPos()) {
                return false;
            }

            if ($node === $expr) {
                return false;
            }

            if (get_class($expr) !== get_class($node)) {
                return false;
            }

            return true;
        });

        return $usedFoundNode instanceof \PhpParser\Node;
    }
}
