<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\If_;
use PHPStan\Analyser\Scope;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class ExprUsedInNextNodeAnalyzer
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private ExprUsedInNodeAnalyzer $exprUsedInNodeAnalyzer
    ) {
    }

    /**
     * $isCheckNameScope parameter is used to whether to check scope of Name that may be renamed
     * @see https://github.com/rectorphp/rector/issues/6675
     */
    public function isUsed(Expr $expr, bool $isCheckNameScope = false): bool
    {
        return (bool) $this->betterNodeFinder->findFirstNext(
            $expr,
            function (Node $node) use ($expr, $isCheckNameScope): bool {
                if ($isCheckNameScope && $node instanceof Name) {
                    $scope = $node->getAttribute(AttributeKey::SCOPE);
                    $resolvedName = $node->getAttribute(AttributeKey::RESOLVED_NAME);
                    $next = $node->getAttribute(AttributeKey::NEXT_NODE);

                    if (! $scope instanceof Scope && ! $resolvedName instanceof Name && $next instanceof Arg) {
                        return true;
                    }
                }

                /**
                 * handle when used along with RemoveUnusedVariableAssignRector and RemoveAlwaysElseRector
                 * which the ElseIf_ gone, changed to If_, and the node structure be:
                 *   - the node is an If_
                 *   - previous statement of node is the expression with assign
                 *   - the next statement of previous statement is not equal to If_, as gone
                 */
                if ($node instanceof If_) {
                    $previousStatement = $node->getAttribute(AttributeKey::PREVIOUS_STATEMENT);
                    if ($previousStatement instanceof Stmt) {
                        $nextStatement = $previousStatement->getAttribute(AttributeKey::NEXT_NODE);
                        return $nextStatement === $node;
                    }
                }

                return $this->exprUsedInNodeAnalyzer->isUsed($node, $expr);
            }
        );
    }
}
