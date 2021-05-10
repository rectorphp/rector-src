<?php

declare(strict_types=1);

namespace Rector\Core\NodeManipulator;

use PhpParser\Node;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\NodeAnalyzer\PropertyFetchAnalyzer;
use Rector\NodeNameResolver\NodeNameResolver;
use Symplify\Astral\NodeTraverser\SimpleCallableNodeTraverser;

final class FunctionLikeManipulator
{
    public function __construct(
        private SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private NodeNameResolver $nodeNameResolver,
        private PropertyFetchAnalyzer $propertyFetchAnalyzer
    ) {
    }

    /**
     * @return string[]
     */
    public function getReturnedLocalPropertyNames(FunctionLike $functionLike): array
    {
        // process only class methods
        if ($functionLike instanceof Function_) {
            return [];
        }

        $returnedLocalPropertyNames = [];
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($functionLike, function (Node $node) use (
            &$returnedLocalPropertyNames
        ) {
            if (! $node instanceof Return_) {
                return null;
            }
            if ($node->expr === null) {
                return null;
            }
            if (! $this->propertyFetchAnalyzer->isLocalPropertyFetch($node->expr)) {
                return null;
            }

            $propertyName = $this->nodeNameResolver->getName($node->expr);
            if ($propertyName === null) {
                return null;
            }

            $returnedLocalPropertyNames[] = $propertyName;
        });

        return $returnedLocalPropertyNames;
    }
}
