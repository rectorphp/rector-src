<?php

declare(strict_types=1);

namespace Rector\ReadWrite\ReadNodeAnalyzer;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Stmt\Expression;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class JustReadExprAnalyzer
{
    public function isReadContext(Expr $expr): bool
    {
        if ($expr->getAttribute(AttributeKey::IS_RETURN_EXPR) === true) {
            return true;
        }

        $parentNode = $expr->getAttribute(AttributeKey::PARENT_NODE);
        if ($parentNode instanceof Arg) {
            return true;
        }

        if ($parentNode instanceof ArrayDimFetch) {
            return $parentNode->getAttribute(AttributeKey::IS_BEING_ASSIGNED) !== true;
        }

        // assume it's used by default
        return ! $parentNode instanceof Expression;
    }
}
