<?php

declare(strict_types=1);

namespace Rector\PostRector\Guard;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\InlineHTML;
use PhpParser\Node\Stmt\Namespace_;
use Rector\PhpParser\Node\BetterNodeFinder;

class AddUseStatementGuard
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder
    ) {
    }

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

        return ! $this->betterNodeFinder->hasInstancesOf($stmts, [InlineHTML::class]);
    }
}
