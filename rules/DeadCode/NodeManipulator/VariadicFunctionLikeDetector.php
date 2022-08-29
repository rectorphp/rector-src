<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeManipulator;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\FunctionLike;
use PhpParser\NodeTraverser;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;

final class VariadicFunctionLikeDetector
{
    /**
     * @var string[]
     */
    private const VARIADIC_FUNCTION_NAMES = ['func_get_arg', 'func_get_args', 'func_num_args'];

    public function __construct(
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private readonly NodeNameResolver $nodeNameResolver
    ) {
    }

    /**
     * @api
     */
    public function isVariadic(FunctionLike $functionLike): bool
    {
        $isVariadic = false;

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
            (array) $functionLike->getStmts(),
            function (Node $node) use (&$isVariadic): ?int {
                if (! $node instanceof FuncCall) {
                    return null;
                }

                if (! $this->nodeNameResolver->isNames($node, self::VARIADIC_FUNCTION_NAMES)) {
                    return null;
                }

                $isVariadic = true;

                return NodeTraverser::STOP_TRAVERSAL;
            }
        );

        return $isVariadic;
    }
}
