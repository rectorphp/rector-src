<?php

declare(strict_types=1);

namespace Rector\ReadWrite\ReadNodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Stmt\Class_;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\PhpParser\NodeFinder\PropertyFetchFinder;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\ReadWrite\Contract\ReadNodeAnalyzerInterface;

final class LocalPropertyFetchReadNodeAnalyzer implements ReadNodeAnalyzerInterface
{
    public function __construct(
        private JustReadExprAnalyzer $justReadExprAnalyzer,
        private PropertyFetchFinder $propertyFetchFinder,
        private NodeNameResolver $nodeNameResolver,
        private BetterNodeFinder $betterNodeFinder
    ) {
    }

    public function supports(Node $node): bool
    {
        return $node instanceof PropertyFetch || $node instanceof StaticPropertyFetch;
    }

    /**
     * @param PropertyFetch $node
     */
    public function isRead(Node $node): bool
    {
        $class = $this->betterNodeFinder->findParentType($node, Class_::class);
        if (! $class instanceof Class_) {
            // assume worse to keep node protected
            return true;
        }

        $propertyName = $this->nodeNameResolver->getName($node->name);
        if ($propertyName === null) {
            // assume worse to keep node protected
            return true;
        }

        $propertyFetches = $this->propertyFetchFinder->findLocalPropertyFetchesByName($class, $propertyName);
        foreach ($propertyFetches as $propertyFetch) {
            if ($this->justReadExprAnalyzer->isReadContext($propertyFetch)) {
                return true;
            }
        }

        return false;
    }
}
