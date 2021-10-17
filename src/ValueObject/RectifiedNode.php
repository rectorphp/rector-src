<?php

declare(strict_types=1);

namespace Rector\Core\ValueObject;

use PhpParser\Node;

final class RectifiedNode
{
    public function __construct(private string $rectorClass, private Node $node)
    {
    }

    public function getRectorClass(): string
    {
        return $this->rectorClass;
    }

    public function getNode(): Node
    {
        return $this->node;
    }
}