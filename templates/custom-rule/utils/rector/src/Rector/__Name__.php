<?php

declare(strict_types=1);

namespace __Namespace__;

use PhpParser\Node;
use Rector\Rector\AbstractRector;

/**
 * @see \__Tests_Namespace__\__Name__\__Name__Test
 */
final class __Name__ extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        // @todo select node type
        return [\PhpParser\Node\Stmt\Class_::class];
    }

    /**
     * @param \PhpParser\Node\Stmt\Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        // @todo change the node

        return $node;
    }
}
