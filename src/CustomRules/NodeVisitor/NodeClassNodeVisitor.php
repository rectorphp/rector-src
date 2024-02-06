<?php

declare(strict_types=1);

namespace Rector\CustomRules\NodeVisitor;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

final class NodeClassNodeVisitor extends NodeVisitorAbstract
{
    /**
     * @var array<class-string<Node>>
     */
    private array $foundNodeClasses = [];

    public function enterNode(Node $node): ?Node
    {
        $this->foundNodeClasses[] = $node::class;

        return null;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getFoundNodeClasses(): array
    {
        return $this->foundNodeClasses;
    }
}
