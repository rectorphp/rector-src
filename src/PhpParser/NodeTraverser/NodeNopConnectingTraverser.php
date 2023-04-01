<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\NodeTraverser;

use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NodeConnectingVisitor;
use Rector\Core\PhpParser\NodeVisitor\NopVisitor;

final class NodeNopConnectingTraverser extends NodeTraverser
{
    public function __construct(NopVisitor $nopVisitor)
    {
        parent::__construct();

        $this->addVisitor(new NodeConnectingVisitor());
        $this->addVisitor($nopVisitor);
    }
}
