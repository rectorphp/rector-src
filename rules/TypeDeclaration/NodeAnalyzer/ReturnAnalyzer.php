<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\NodeTraverser;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\TypeDeclaration\TypeInferer\SilentVoidResolver;

final readonly class ReturnAnalyzer
{
    public function __construct(
        private SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private SilentVoidResolver $silentVoidResolver
    ) {
    }

    public function hasOnlyReturnWithExpr(ClassMethod|Function_ $functionLike): bool
    {
        if ($functionLike->stmts === null) {
            return false;
        }

        $hasOnlyReturnWithExpr = false;

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
            $functionLike->stmts,
            function (Node $subNode) use (&$hasOnlyReturnWithExpr): ?int {
                if ($subNode instanceof Class_ || $subNode instanceof Function_ || $subNode instanceof Closure) {
                    return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                }

                if (! $subNode instanceof Return_) {
                    return null;
                }

                if ($subNode->expr instanceof Expr) {
                    $hasOnlyReturnWithExpr = true;
                    return null;
                }

                // stop when found a Return_ without Expr
                $hasOnlyReturnWithExpr = false;
                return NodeTraverser::STOP_TRAVERSAL;
            }
        );

        if (! $hasOnlyReturnWithExpr) {
            return false;
        }

        return ! $this->silentVoidResolver->hasSilentVoid($functionLike);
    }
}
