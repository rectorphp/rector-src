<?php

declare(strict_types=1);

namespace Rector\Core\Application;

use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Analyser\NodeScopeResolver;
use PHPStan\Analyser\Scope;
use Rector\NodeTypeResolver\PHPStan\Scope\PHPStanNodeScopeResolver;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * In case of changed node, we need to re-traverse the PHPStan Scope to make all the new nodes aware of what is going on.
 */
final class ChangedNodeScopeRefresher
{
    public function __construct(
        private readonly PHPStanNodeScopeResolver $phpStanNodeScopeResolver,
        private readonly NodeScopeResolver $nodeScopeResolver,
    ) {
    }

    public function refresh(Expr|Stmt $node, SmartFileInfo $smartFileInfo, Scope $currentScope): void
    {
        // note from flight: when we traverse ClassMethod, the scope must be already in Class_, otherwise it crashes
        // so we need to somehow get a parent scope that is already in the same place the $node is

        if ($node instanceof Stmt) {
            $stmts = [$node];
        } else {
            $stmts = [new Expression($node)];
        }

        $this->phpStanNodeScopeResolver->processNodes($stmts, $smartFileInfo, $currentScope);
    }
}
