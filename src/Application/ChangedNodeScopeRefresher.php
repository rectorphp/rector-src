<?php

declare(strict_types=1);

namespace Rector\Core\Application;

use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use Rector\NodeTypeResolver\PHPStan\Scope\PHPStanNodeScopeResolver;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * In case of changed node, we need to re-traverse the PHPStan Scope to make all the new nodes aware of what is going on.
 */
final class ChangedNodeScopeRefresher
{
    public function __construct(
        private readonly PHPStanNodeScopeResolver $phpStanNodeScopeResolver
    ) {
    }

    public function refresh(Expr|Stmt $node, SmartFileInfo $smartFileInfo): void
    {
        if ($node instanceof Stmt) {
            $this->phpStanNodeScopeResolver->processNodes([$node], $smartFileInfo);
        } else {
            $stmt = new Expression($node);
            $this->phpStanNodeScopeResolver->processNodes([$stmt], $smartFileInfo);
        }
    }
}
