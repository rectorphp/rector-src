<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\RemoveDuplicatedIfReturnRector\Fixture;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeTraverser;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;

final class SkipParamReference
{
    public function __construct(
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser
    ) {
    }

    public function run(Node\Stmt $stmt): void
    {
        $isParamUsed = false;

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($stmt, function (Node $node) use (
            &$isParamUsed,
        ): ?int {
            if ($isParamUsed) {
                return NodeTraverser::STOP_TRAVERSAL;
            }

            // skip nested anonymous class
            if ($node instanceof Class_ || $node instanceof Function_) {
                $isParamUsed = true;

                return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }

            return null;
        });
    }
}
