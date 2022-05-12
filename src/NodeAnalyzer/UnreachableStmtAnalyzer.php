<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class UnreachableStmtAnalyzer
{
    public function __construct(private readonly BetterNodeFinder $betterNodeFinder)
    {
    }

    public function isStmtPHPStanUnreachable(Node $node): bool
    {
        $isUnreachable = $node->getAttribute(AttributeKey::IS_UNREACHABLE);

        if ($isUnreachable === true) {
            return true;
        }

        $currentStmt = $this->betterNodeFinder->resolveCurrentStatement($node);
        if (! $currentStmt instanceof Stmt) {
            return false;
        }

        $isUnreachable = $currentStmt->getAttribute(AttributeKey::IS_UNREACHABLE);

        return $isUnreachable === true;
    }
}
