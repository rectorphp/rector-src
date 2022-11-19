<?php

declare(strict_types=1);

namespace Rector\Core\NodeDecorator;

use PhpParser\Node;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class CreatedByRuleDecorator
{
    /**
     * @param array<Node>|Node $node
     */
    public function decorate(array | Node $node, Node $originalNode, string $rectorClass): void
    {
        if ($node instanceof Node) {
            $node = [$node];
        }

        foreach ($node as $singleNode) {
            if ($singleNode::class === $originalNode::class) {
                $this->createByRule($singleNode, $rectorClass);
            }
        }

        $this->createByRule($originalNode, $rectorClass);
    }

    private function createByRule(Node $node, string $rectorClass): void
    {
        $createdByRule = $node->getAttribute(AttributeKey::CREATED_BY_RULE) ?? [];
        $lastRectorRuleKey = array_key_last($createdByRule);

        // empty array, insert
        if ($lastRectorRuleKey === null) {
            $node->setAttribute(AttributeKey::CREATED_BY_RULE, [$rectorClass]);
            return;
        }

        // consecutive, no need to refill
        if ($createdByRule[$lastRectorRuleKey] === $rectorClass) {
            return;
        }

        // filter out when exists, then append
        $createdByRule = array_filter(
            $createdByRule,
            static fn (string $rectorRule): bool => $rectorRule !== $rectorClass
        );
        $node->setAttribute(AttributeKey::CREATED_BY_RULE, [...$createdByRule, $rectorClass]);
    }
}
