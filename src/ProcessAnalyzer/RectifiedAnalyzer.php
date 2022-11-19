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
 *      - just re-printed but token start overlapped and still >= 0
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

        return ! $this->shouldContinue($node, $originalNode);
    }

    /**
     * @param class-string<RectorInterface> $rectorClass
     */
    private function hasConsecutiveCreatedByRule(string $rectorClass, Node $node, ?Node $originalNode): bool
    {
        $createdByRuleNode = $originalNode ?? $node;
        $createdByRule = $createdByRuleNode->getAttribute(AttributeKey::CREATED_BY_RULE) ?? [];

        $lastRectorRuleKey = array_key_last($createdByRule);
        if ($lastRectorRuleKey === null) {
            return false;
        }

        return $createdByRule[$lastRectorRuleKey] === $rectorClass;
    }

    private function shouldContinue(Node $node, ?Node $originalNode): bool
    {
        if ($originalNode instanceof Node) {
            return true;
        }

        $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);

        if (! $parentNode instanceof Node) {
            return true;
        }

        $parentOriginalNode = $parentNode->getAttribute(AttributeKey::ORIGINAL_NODE);
        if ($parentOriginalNode instanceof Node) {
            return true;
        }

        /**
         * Start token pos must be < 0 to continue, as the node and parent node just re-printed
         *
         * - Node's original node is null
         * - Parent Node's original node is null
         */
        $startTokenPos = $node->getStartTokenPos();
        return $startTokenPos < 0;
    }
}
