<?php

declare(strict_types=1);

namespace Rector\Core\ProcessAnalyzer;

use PhpParser\Node;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\RectifiedNode;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * This service verify if the Node already rectified with same Rector rule before current Rector rule with condition
 *
 *        Same Rector Rule <-> Same Node <-> Same File
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

    public function verify(RectorInterface $rector, Node $node, File $currentFile): ?RectifiedNode
    {
        $smartFileInfo = $currentFile->getSmartFileInfo();
        $realPath = $smartFileInfo->getRealPath();

        if (! isset($this->previousFileWithNodes[$realPath])) {
            $this->previousFileWithNodes[$realPath] = new RectifiedNode($rector::class, $node);
            return null;
        }

        /** @var RectifiedNode $rectifiedNode */
        $rectifiedNode = $this->previousFileWithNodes[$realPath];
        if ($this->shouldContinue($rectifiedNode, $rector, $node)) {
            return null;
        }

        // re-set to refill next
        $this->previousFileWithNodes[$realPath] = null;
        return $rectifiedNode;
    }

    private function shouldContinue(RectifiedNode $rectifiedNode, RectorInterface $rector, Node $node): bool
    {
        $originalNode = $node->getAttribute(AttributeKey::ORIGINAL_NODE);
        if ($rectifiedNode->getRectorClass() === $rector::class && $rectifiedNode->getNode() === $node) {
            /**
             * allow to revisit the Node with same Rector rule if Node is changed by other rule
             */
            return ! $this->nodeComparator->areNodesEqual($originalNode, $node);
        }

        if ($originalNode instanceof Node) {
            return true;
        }

        $startTokenPos = $node->getStartTokenPos();
        return $startTokenPos < 0;
    }
}
