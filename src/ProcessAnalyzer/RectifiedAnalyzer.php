<?php

declare(strict_types=1);

namespace Rector\ProcessAnalyzer;

use PhpParser\Node;
use Rector\Contract\Rector\RectorInterface;
use Rector\NodeAnalyzer\ScopeAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * This service verify if the Node:
 *
 *      - already applied same Rector rule before current Rector rule on last previous Rector rule.
 *      - just re-printed but token start still >= 0
 *      - has above node skipped traverse children on current rule
 */
final readonly class RectifiedAnalyzer
{
    public function __construct(
        private ScopeAnalyzer $scopeAnalyzer
    ) {
    }

    /**
     * @param class-string<RectorInterface> $rectorClass
     */
    public function hasRectified(string $rectorClass, Node $node): bool
    {
        $originalNode = $node->getAttribute(AttributeKey::ORIGINAL_NODE);

        if ($this->hasConsecutiveCreatedByRule($rectorClass, $node, $originalNode)) {
            return true;
        }

        if ($this->isJustReprintedOverlappedTokenStart($node, $originalNode)) {
            return true;
        }

        return $node->getAttribute(AttributeKey::SKIPPED_BY_RECTOR_RULE) === $rectorClass;
    }

    /**
     * @param class-string<RectorInterface> $rectorClass
     */
    private function hasConsecutiveCreatedByRule(string $rectorClass, Node $node, ?Node $originalNode): bool
    {
        $createdByRuleNode = $originalNode ?? $node;
        /** @var class-string<RectorInterface>[] $createdByRule */
        $createdByRule = $createdByRuleNode->getAttribute(AttributeKey::CREATED_BY_RULE) ?? [];

        if ($createdByRule === []) {
            return false;
        }

        return end($createdByRule) === $rectorClass;
    }

    private function isJustReprintedOverlappedTokenStart(Node $node, ?Node $originalNode): bool
    {
        if ($originalNode instanceof Node) {
            return false;
        }

        /**
         * Start token pos must be < 0 to continue, as the node and parent node just re-printed
         *
         * - Node's original node is null
         * - Parent Node's original node is null
         */
        $startTokenPos = $node->getStartTokenPos();
        if ($startTokenPos >= 0) {
            return true;
        }

        if (! $this->scopeAnalyzer->isRefreshable($node)) {
            return false;
        }

        return ! in_array(AttributeKey::SCOPE, array_keys($node->getAttributes()), true);
    }
}
