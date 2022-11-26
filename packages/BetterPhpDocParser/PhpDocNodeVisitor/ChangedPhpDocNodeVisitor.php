<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\PhpDocNodeVisitor;

use PHPStan\PhpDocParser\Ast\Node;
use Rector\BetterPhpDocParser\ValueObject\PhpDocAttributeKey;
use Rector\BetterPhpDocParser\ValueObject\StartAndEnd;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeVisitor\AbstractPhpDocNodeVisitor;

final class ChangedPhpDocNodeVisitor extends AbstractPhpDocNodeVisitor
{
    private bool $hasChanged = false;

    public function beforeTraverse(Node $node): void
    {
        $this->hasChanged = false;
    }

    public function enterNode(Node $node): ?Node
    {
        $origNode = $node->getAttribute(PhpDocAttributeKey::ORIG_NODE);
        if (! $origNode instanceof Node) {
            $this->hasChanged = true;
            return $node;
        }

        $nodeStartAndEnd = $node->getAttribute(PhpDocAttributeKey::START_AND_END);
        $origNodeStartAndEnd = $origNode->getAttribute(PhpDocAttributeKey::START_AND_END);

        if (! $nodeStartAndEnd instanceof StartAndEnd) {
            return null;
        }

        if (! $origNodeStartAndEnd instanceof StartAndEnd) {
            return null;
        }

        if ($nodeStartAndEnd->getStart() !== $origNodeStartAndEnd->getStart() || $nodeStartAndEnd->getEnd() !== $origNodeStartAndEnd->getEnd()) {
            $this->hasChanged = true;
            return $node;
        }

        return null;
    }

    public function hasChanged(): bool
    {
        return $this->hasChanged;
    }
}
