<?php

declare(strict_types=1);

namespace Rector\Core\ProcessAnalyzer;

use PhpParser\Node;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * This service verify if the Node:
 *
 *      - already applied same Rector rule before current Rector rule on last previous Rector rule.
 *      - just re-printed but token start still >= 0
 */
final class RectifiedAnalyzer
{
    /**
     * @param class-string<RectorInterface> $rectorClass
     */
    public function hasRectified(string $rectorClass, Node $node): bool
    {
        $originalNode = $node->getAttribute(AttributeKey::ORIGINAL_NODE);

        if ($this->hasConsecutiveCreatedByRule($rectorClass, $node, $originalNode)) {
            return true;
        }

        return $this->isJustReprintedOverlappedTokenStart($node, $originalNode);
    }

    /**
     * @param class-string<RectorInterface> $rectorClass
     */
    private function hasConsecutiveCreatedByRule(string $rectorClass, Node $node, ?Node $originalNode): bool
    {
        $createdByRuleNode = $originalNode ?? $node;
        /** @var class-string<RectorInterface>[] $createdByRule */
        $createdByRule = $createdByRuleNode->getAttribute(AttributeKey::CREATED_BY_RULE) ?? [];

        $lastRectorRuleKey = array_key_last($createdByRule);
        if ($lastRectorRuleKey === null) {
            return false;
        }

        return $createdByRule[$lastRectorRuleKey] === $rectorClass;
    }

    /**
     * Start token pos must be < 0 to continue if the node and/or parent node just re-printed
     *
     * - Node's original node is Node -> continue
     * - Node's original node is null
     *      - with no parent and Node start token pos >= 0 -> stop
     *      - has parent with original node a Node -> continue
     *      - parent original Node is null and parent start token post >=0 -> stop
     */
    private function isJustReprintedOverlappedTokenStart(Node $node, ?Node $originalNode): bool
    {
        if ($originalNode instanceof Node) {
            return false;
        }

        $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);

        if (! $parentNode instanceof Node) {
            return $node->getStartTokenPos() >= 0;
        }

        $parentOriginalNode = $parentNode->getAttribute(AttributeKey::ORIGINAL_NODE);
        if ($parentOriginalNode instanceof Node) {
            return false;
        }

        return $parentNode->getStartTokenPos() >= 0;
    }
}
