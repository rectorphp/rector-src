<?php

declare(strict_types=1);

namespace Rector\NodeNameResolver\NodeNameResolver;

use PhpParser\Node;
use PhpParser\Node\Stmt\Namespace_;
use Rector\NodeNameResolver\Contract\NodeNameResolverInterface;

final class NamespaceNameResolver implements NodeNameResolverInterface
{
    /**
     * @return class-string<Node>
     */
    public function getNode(): string
    {
        return Namespace_::class;
    }

    /**
     * @param Namespace_ $node
     */
    public function resolve(Node $node): ?string
    {
        if ($node->name === null) {
            return null;
        }

        return $node->name->toString();
    }
}
