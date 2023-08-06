<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\NodeTypeResolver;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\Type;
use PHPStan\Type\VoidType;
use Rector\NodeTypeResolver\Contract\NodeTypeResolverAwareInterface;
use Rector\NodeTypeResolver\Contract\NodeTypeResolverInterface;
use Rector\NodeTypeResolver\NodeTypeResolver;

/**
 * @implements NodeTypeResolverInterface<Return_>
 */
final class ReturnTypeResolver implements NodeTypeResolverInterface, NodeTypeResolverAwareInterface
{
    private NodeTypeResolver $nodeTypeResolver;

    public function autowire(NodeTypeResolver $nodeTypeResolver): void
    {
        $this->nodeTypeResolver = $nodeTypeResolver;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeClasses(): array
    {
        return [Return_::class];
    }

    /**
     * @param Return_ $node
     */
    public function resolve(Node $node): Type
    {
        if (! $node->expr instanceof Expr) {
            return new VoidType();
        }

        return $this->nodeTypeResolver->getType($node->expr);
    }
}
