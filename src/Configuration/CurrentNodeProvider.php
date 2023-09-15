<?php

declare(strict_types=1);

namespace Rector\Core\Configuration;

use PhpParser\Node;

/**
 * @api
 * @deprecated Pass node directly
 */
final class CurrentNodeProvider
{
    private ?Node $node = null;

    public function setNode(Node $node): void
    {
        $this->node = $node;
    }

    public function getNode(): ?Node
    {
        return $this->node;
    }
}
