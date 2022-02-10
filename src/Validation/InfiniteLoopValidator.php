<?php

declare(strict_types=1);

namespace Rector\Core\Validation;

use PhpParser\Node;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class InfiniteLoopValidator
{
    public function isValid(Node $originalNode, string $rectorClass): bool
    {
        $createdByRule = $originalNode->getAttribute(AttributeKey::CREATED_BY_RULE) ?? [];
        return ! in_array($rectorClass, $createdByRule, true);
    }
}
