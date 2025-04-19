<?php

declare(strict_types=1);

namespace Rector\Tests\PostRector\AdditionalPostRector\Source;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use Rector\PostRector\Rector\AbstractPostRector;

final class CommentRemoverPostRector extends AbstractPostRector
{
    public function enterNode(Node $node): Node|null
    {
        if (! $node instanceof Stmt ) {
            return null;
        }

        if ($node->getComments() === []) {
            return null;
        }
        $node->setAttribute('comments', []);
        return $node;
    }

    public function shouldTraverse(array $stmts): bool
    {
        //Only remove comments if we have a namespace
         return $stmts !== [] && $stmts[0] instanceof Stmt\Namespace_;
    }

    public function getPriority(): int
    {
        return 450;
    }
}
