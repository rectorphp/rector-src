<?php

declare(strict_types=1);

namespace Rector\Core\ProcessAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use Rector\Core\Contract\Rector\RectorInterface;
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

        if ($rectifiedNode->getRectorClass() === $rector::class && $rectifiedNode->getNode() === $node) {
            // re-set to refill next
            $this->previousFileWithNodes[$realPath] = null;
            return $rectifiedNode;
        }

        if ($node instanceof Stmt) {
            return null;
        }

        $stmt = $node->getAttribute(AttributeKey::CURRENT_STATEMENT);
        if ($stmt instanceof Stmt) {
            return null;
        }

        $startTokenPos = $node->getStartTokenPos();
        $endTokenPos = $node->getEndTokenPos();
        if ($startTokenPos < 0) {
            return null;
        }

        if ($endTokenPos < 0) {
            return null;
        }

        // re-set to refill next
        $this->previousFileWithNodes[$realPath] = null;
        return $rectifiedNode;
    }
}
