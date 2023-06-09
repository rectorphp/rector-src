<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\NodeTypeResolver;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeTraverser;
use PHPStan\Analyser\Scope;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\NodeTypeResolver\Contract\NodeTypeResolverInterface;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @see \Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\ParamTypeResolver\ParamTypeResolverTest
 *
 * @implements NodeTypeResolverInterface<Param>
 */
final class ParamTypeResolver implements NodeTypeResolverInterface
{
    private NodeTypeResolver $nodeTypeResolver;

    #[Required]
    public function autowire(NodeTypeResolver $nodeTypeResolver): void
    {
        $this->nodeTypeResolver = $nodeTypeResolver;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeClasses(): array
    {
        return [Param::class];
    }

    /**
     * @param Param $node
     */
    public function resolve(Node $node, ?Scope $scope): Type
    {
        if ($node->type === null) {
            return new MixedType();
        }

        return $this->nodeTypeResolver->getType($node->type);
    }
}
