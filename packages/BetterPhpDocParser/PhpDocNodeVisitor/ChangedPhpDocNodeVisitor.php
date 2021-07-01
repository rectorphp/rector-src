<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\PhpDocNodeVisitor;

use PHPStan\PhpDocParser\Ast\Node;
use Rector\BetterPhpDocParser\ValueObject\PhpDoc\DoctrineAnnotation\AbstractValuesAwareNode;
use Rector\BetterPhpDocParser\ValueObject\PhpDocAttributeKey;
use Symplify\SimplePhpDocParser\PhpDocNodeVisitor\AbstractPhpDocNodeVisitor;

final class ChangedPhpDocNodeVisitor extends AbstractPhpDocNodeVisitor
{
    private bool $hasChanged = false;

    public function beforeTraverse(Node $node): void
    {
        $this->hasChanged = false;
    }

    public function enterNode(Node $node): ?Node
    {
        if ($node->hasAttribute(PhpDocAttributeKey::ORIG_NODE)) {
            $origNode = $node->getAttribute(PhpDocAttributeKey::ORIG_NODE);
            if ($origNode === null) {
                $this->hasChanged = true;
                return $node;
            }
        }
        if (! $node instanceof AbstractValuesAwareNode) {
            return null;
        }
        if (! $node->hasChanged()) {
            return null;
        }
        $this->hasChanged = true;
        return null;
    }

    public function hasChanged(): bool
    {
        return $this->hasChanged;
    }
}
