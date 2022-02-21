<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PhpDoc\PhpDocNodeTraverser;

use Rector\NodeTypeResolver\PhpDocNodeVisitor\UnderscoreRenamePhpDocNodeVisitor;
use Symplify\Astral\PhpDocParser\PhpDocNodeTraverser;

final class UnderscorePhpDocNodeTraverserFactory
{
    public function __construct(
        private readonly UnderscoreRenamePhpDocNodeVisitor $underscoreRenamePhpDocNodeVisitor
    ) {
    }

    public function create(): PhpDocNodeTraverser
    {
        $phpDocNodeTraverser = new PhpDocNodeTraverser();
        $phpDocNodeTraverser->addPhpDocNodeVisitor($this->underscoreRenamePhpDocNodeVisitor);

        return $phpDocNodeTraverser;
    }
}
