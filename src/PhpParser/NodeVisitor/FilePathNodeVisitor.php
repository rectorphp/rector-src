<?php

declare(strict_types=1);

namespace Rector\PhpParser\NodeVisitor;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class FilePathNodeVisitor extends NodeVisitorAbstract
{
    public function __construct(
        private string $filePath
    ) {
    }

    public function enterNode(Node $node): \PhpParser\Node
    {
        $node->setAttribute(AttributeKey::FILE_PATH, $this->filePath);

        return $node;
    }
}
