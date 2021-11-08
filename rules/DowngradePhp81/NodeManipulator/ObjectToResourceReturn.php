<?php

declare(strict_types=1);

namespace Rector\Php81\NodeManipulator;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Name\FullyQualified;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\PhpParser\Node\NodeFactory;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;

final class ObjectToResourceReturn
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private NodeTypeResolver $nodeTypeResolver,
        private BetterNodeFinder $betterNodeFinder,
        private NodeComparator $nodeComparator,
        private NodeFactory $nodeFactory
    ) {
    }

    /**
     * @param array<string, string> $collectionObjectToResource
     */
    public function refactor(
        Instanceof_ $instanceof,
        array $collectionObjectToResource
    ): ?FuncCall {
        if (! $instanceof->class instanceof FullyQualified) {
            return null;
        }

        $className = $instanceof->class->toString();
        foreach ($collectionObjectToResource as $singleCollectionObjectToResource) {
            if ($singleCollectionObjectToResource === $className) {
                return $this->nodeFactory->createFuncCall('is_resource', [$instanceof->expr]);
            }
        }

        return null;
    }
}
