<?php

declare(strict_types=1);

namespace Rector\ReadWrite\ReadNodeAnalyzer;

use PhpParser\Node\Expr;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class JustReadExprAnalyzer
{
    public function isReadContext(Expr $expr): bool
    {
        return $expr->getAttribute(AttributeKey::IS_READ_CONTEXT) === true;
    }
}
