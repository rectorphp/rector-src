<?php

declare(strict_types=1);

namespace Rector\CodingStyle\ValueObject;

use PhpParser\Node;
use PhpParser\Node\Expr;

final class NodeToRemoveAndConcatItem
{
    public function __construct(
        private Expr $expr,
        private Node $node
    ) {
    }

    public function getRemovedExpr(): Expr
    {
        return $this->expr;
    }

    public function getConcatItemNode(): Node
    {
        return $this->node;
    }
}
