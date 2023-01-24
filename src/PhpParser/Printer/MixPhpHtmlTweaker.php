<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\Printer;

use PhpParser\Node;
use PhpParser\Node\Stmt\InlineHTML;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class MixPhpHtmlTweaker
{
    public function before(Node $node): void
    {
        $firstNodePreviousNode = $node->getAttribute(AttributeKey::PREVIOUS_NODE);
        if ($firstNodePreviousNode instanceof InlineHTML && ! $node instanceof InlineHTML) {
            // re-print InlineHTML is safe
            $firstNodePreviousNode->setAttribute(AttributeKey::ORIGINAL_NODE, null);
        }
    }
}