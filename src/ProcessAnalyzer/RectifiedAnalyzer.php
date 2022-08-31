<?php

declare(strict_types=1);

namespace Rector\Core\ProcessAnalyzer;

use PhpParser\Node;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\ValueObject\RectifiedNode;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * This service verify if the Node already rectified with same Rector rule before current Rector rule with condition
 *
 *        Same Rector Rule <-> Same Node <-> Same File
 *
 *  For both non-consecutive or consecutive order.
 */
final class RectifiedAnalyzer
{
    /**
     * @var array<string, RectifiedNode|null>
     */
    private array $previousFileWithNodes = [];

    public function __construct(private readonly NodeComparator $nodeComparator)
    {
    }

    /**
     * @param class-string<RectorInterface> $rectorClass
     */
    public function verify(string $rectorClass, Node $node, string $filePath): ?RectifiedNode
    {
        $originalNode = $node->getAttribute(AttributeKey::ORIGINAL_NODE);

        if ($this->hasCreatedByRule($rectorClass, $node, $originalNode)) {
            return new RectifiedNode($rectorClass, $node);
        }

        //        $realPath = $smartFileInfo->getRealPath();

        if (! isset($this->previousFileWithNodes[$filePath])) {
            $this->previousFileWithNodes[$filePath] = new RectifiedNode($rectorClass, $node);
            return null;
        }

        /** @var RectifiedNode $rectifiedNode */
        $rectifiedNode = $this->previousFileWithNodes[$filePath];
        if ($this->shouldContinue($rectifiedNode, $rectorClass, $node, $originalNode)) {
            return null;
        }

        if ($this->previousFileWithNodes[$filePath]->getNode() === $node) {
            // re-set to refill next
            $this->previousFileWithNodes[$filePath] = null;
        }

        return $rectifiedNode;
    }

    /**
     * @param class-string<RectorInterface> $rectorClass
     */
    private function hasCreatedByRule(string $rectorClass, Node $node, ?Node $originalNode): bool
    {
        $originalNode ??= $node;
        $createdByRule = $originalNode->getAttribute(AttributeKey::CREATED_BY_RULE) ?? [];
        return in_array($rectorClass, $createdByRule, true);
    }

    /**
     * @param class-string<RectorInterface> $rectorClass
     */
    private function shouldContinue(
        RectifiedNode $rectifiedNode,
        string $rectorClass,
        Node $node,
        ?Node $originalNode
    ): bool {
        $rectifiedNodeClass = $rectifiedNode->getRectorClass();
        $rectifiedNodeNode = $rectifiedNode->getNode();

        if ($rectifiedNodeClass === $rectorClass && $rectifiedNodeNode === $node) {
            /**
             * allow to revisit the Node with same Rector rule if Node is changed by other rule
             */
            return ! $this->nodeComparator->areNodesEqual($originalNode, $node);
        }

        if ($originalNode instanceof Node) {
            return true;
        }

        if ($this->isPreviousCreatedByRuleAttributeEquals($rectifiedNodeClass, $rectifiedNodeNode, $node)) {
            return true;
        }

        $startTokenPos = $node->getStartTokenPos();
        return $startTokenPos < 0;
    }

    /**
     * @param class-string<RectorInterface> $rectifiedNodeClass
     */
    private function isPreviousCreatedByRuleAttributeEquals(
        string $rectifiedNodeClass,
        Node $rectifiedNodeNode,
        Node $node
    ): bool {
        /** @var class-string<RectorInterface>[] $createdByRule */
        $createdByRule = $node->getAttribute(AttributeKey::CREATED_BY_RULE) ?? [];
        if (count($createdByRule) !== 1) {
            return false;
        }

        // different rule, allowed
        if (current($createdByRule) !== $rectifiedNodeClass) {
            return true;
        }

        return $rectifiedNodeNode === $node;
    }
}
