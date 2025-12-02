<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Stmt\If_;
use PhpParser\NodeVisitorAbstract;
use Rector\Contract\PhpParser\DecoratingNodeVisitorInterface;
use Rector\DeadCode\ConditionResolver;
use Rector\DeadCode\ValueObject\VersionCompareCondition;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpParser\NodeTraverser\SimpleNodeTraverser;

final class PhpVersionConditionNodeVisitor extends NodeVisitorAbstract implements DecoratingNodeVisitorInterface
{
    public function __construct(
        private readonly ConditionResolver $conditionResolver
    ) {
    }

    public function enterNode(Node $node): ?Node
    {
        if (($node instanceof Ternary || $node instanceof If_) && $this->hasVersionCompareCond($node)) {
            if ($node instanceof Ternary) {
                $nodes = [$node->else];
                if ($node->if instanceof \PhpParser\Node) {
                    $nodes[] = $node->if;
                }
            } else {
                $nodes = $node->stmts;
            }

            SimpleNodeTraverser::decorateWithAttributeValue($nodes, AttributeKey::PHP_VERSION_CONDITIONED, true);
        }

        return null;
    }

    private function hasVersionCompareCond(If_|Ternary $ifOrTernary): bool
    {
        if (! $ifOrTernary->cond instanceof FuncCall) {
            return false;
        }

        $versionCompare = $this->conditionResolver->resolveFromExpr($ifOrTernary->cond);
        return $versionCompare instanceof VersionCompareCondition;
    }
}
