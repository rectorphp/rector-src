<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\NodeTypeResolver;

use PhpParser\Node;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\Type;
use PHPStan\Type\VoidType;
use Rector\NodeTypeResolver\Contract\NodeTypeResolverInterface;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @implements NodeTypeResolverInterface<Return_>
 */
final class ReturnTypeResolver implements NodeTypeResolverInterface
{
    private NodeTypeResolver $nodeTypeResolver;

    #[Required]
    public function autowire(NodeTypeResolver $nodeTypeResolver): void
    {
        $this->nodeTypeResolver = $nodeTypeResolver;
    }

    public function getNodeClasses(): array
    {
        return [Return_::class];
    }

    /**
     * @param Return_ $node
     */
    public function resolve(Node $node): Type
    {
        if ($node->expr === null) {
            return new VoidType();
        }

        return $this->nodeTypeResolver->getType($node->expr);
    }
}
