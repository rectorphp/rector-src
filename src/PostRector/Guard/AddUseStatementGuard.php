<?php

declare(strict_types=1);

namespace Rector\PostRector\Guard;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\InlineHTML;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\NodeFinder;

class AddUseStatementGuard
{
    /**
     * @param Stmt[] $stmts
     */
    public function shouldTraverse(array $stmts): bool
    {
        $totalNamespaces = 0;

        // just loop the first level stmts to locate namespace to improve performance
        // as namespace is always on first level
        foreach ($stmts as $stmt) {
            if ($stmt instanceof Namespace_) {
                ++$totalNamespaces;
            }

            // skip if 2 namespaces are present
            if ($totalNamespaces === 2) {
                return false;
            }
        }

        $nodeFinder = new NodeFinder();
        return ! (bool) $nodeFinder->findFirstInstanceOf($stmts, InlineHTML::class);
    }
}
