<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\NodeTraverser;

use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;

final class CleanVisitorNodeTraverser extends NodeTraverser
{
    public function addVisitor(NodeVisitor $nodeVisitor): void
    {
        $this->visitors = [$nodeVisitor];
    }
}
