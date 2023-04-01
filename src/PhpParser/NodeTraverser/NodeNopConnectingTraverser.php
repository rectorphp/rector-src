<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\NodeTraverser;

use Rector\Core\PhpParser\NodeVisitor\NopVisitor;

final class NodeNopConnectingTraverser extends NodeConnectingTraverser
{
    public function __construct(NopVisitor $nopVisitor)
    {
        parent::__construct();
        $this->addVisitor($nopVisitor);
    }
}
