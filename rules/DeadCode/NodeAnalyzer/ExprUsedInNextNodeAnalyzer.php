<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Name;
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
     *
     * $hasIfConditionCheck parameter is used to check whether next is an if else if that may be removed by RemoveAlwaysElseRector
     * @see https://github.com/rectorphp/rector/issues/6819
     */
    public function isUsed(Expr $expr, bool $isCheckNameScope = false, bool $hasIfConditionCheck = false): bool
    {
        return (bool) $this->betterNodeFinder->findFirstNext(
            $expr,
            function (Node $node) use ($expr, $isCheckNameScope, $hasIfConditionCheck): bool {
                if ($hasIfConditionCheck && $node instanceof If_) {
                    return true;
                }

                if ($isCheckNameScope && $node instanceof Name) {
                    $scope = $node->getAttribute(AttributeKey::SCOPE);
                    $resolvedName = $node->getAttribute(AttributeKey::RESOLVED_NAME);
                    $next = $node->getAttribute(AttributeKey::NEXT_NODE);

                    if (! $scope instanceof Scope && ! $resolvedName instanceof Name && $next instanceof Arg) {
                        return true;
                    }
                }

                return $this->exprUsedInNodeAnalyzer->isUsed($node, $expr);
            }
        );
    }
}
