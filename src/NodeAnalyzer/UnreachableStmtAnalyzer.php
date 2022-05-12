<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node\Stmt;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class UnreachableStmtAnalyzer
{
    public function isStmtPHPStanUnreachable(Stmt $node): bool
    {
        $isUnreachable = $node->getAttribute(AttributeKey::IS_UNREACHABLE);
        return $isUnreachable === true;
    }
}
