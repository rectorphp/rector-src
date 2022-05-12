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
        $currentStmt = $this->betterNodeFinder->resolveCurrentStatement($node);
        return $currentStmt instanceof Stmt
            ? $currentStmt->getAttribute(AttributeKey::IS_UNREACHABLE) === true
            : false;
    }
}
