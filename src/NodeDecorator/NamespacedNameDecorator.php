<?php

declare(strict_types=1);

namespace Rector\Core\NodeDecorator;

use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;

final class NamespacedNameDecorator
{
    /**
     * @param \PhpParser\Node|\PhpParser\Node[] $node
     */
    public function decorate(\PhpParser\Node|array $node): void
    {
        if (is_array($node)) {
            $nodes = $node;
        } else {
            $nodes = [$node];
        }

        // traverse with node name resolver, to to comply with PHPStan default parser
        $nameResolver = new NameResolver(null, [
            'replaceNodes' => false,
            'preserveOriginalNames' => true,
        ]);
        $nodeTraverser = new NodeTraverser();
        $nodeTraverser->addVisitor($nameResolver);
        $nodeTraverser->traverse($nodes);
    }
}
