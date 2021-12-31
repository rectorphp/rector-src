<?php

declare(strict_types=1);

namespace Rector\Core\NodeDecorator;

use PhpParser\Node;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class CreatedByRuleDecorator
{
    public function decorate(Node $node, string $rectorClass): void
    {
        $mergeCreatedByRule = array_merge($node->getAttribute(AttributeKey::CREATED_BY_RULE) ?? [], [$rectorClass]);
        $mergeCreatedByRule = array_unique($mergeCreatedByRule);

        $node->setAttribute(AttributeKey::CREATED_BY_RULE, $mergeCreatedByRule);
    }
}
