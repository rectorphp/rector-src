<?php

declare(strict_types=1);

namespace Rector\NodeNestingScope;

use PhpParser\Node;
use PhpParser\Node\Expr\NullsafePropertyFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class ContextAnalyzer
{
    /**
     * @api
     */
    public function isInLoop(Node $node): bool
    {
        return $node->getAttribute(AttributeKey::IS_IN_LOOP) === true;
    }

    /**
     * @api
     */
    public function isInIf(Node $node): bool
    {
        return $node->getAttribute(AttributeKey::IS_IN_IF) === true;
    }

    public function isChangeableContext(PropertyFetch | StaticPropertyFetch | NullsafePropertyFetch $propertyFetch): bool
    {
        if ($propertyFetch->getAttribute(AttributeKey::IS_UNSET_VAR, false)) {
            return true;
        }

        if ($propertyFetch->getAttribute(AttributeKey::INSIDE_ARRAY_DIM_FETCH, false)) {
            return true;
        }

        if ($propertyFetch->getAttribute(AttributeKey::IS_USED_AS_ARG_BY_REF_VALUE, false) === true) {
            return true;
        }

        return $propertyFetch->getAttribute(AttributeKey::IS_INCREMENT_OR_DECREMENT, false) === true;
    }
}
