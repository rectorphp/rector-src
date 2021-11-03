<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\NodeTypeResolver;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\Type;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeTypeResolver\Contract\NodeTypeResolverInterface;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

final class ClassMethodOrClassConstTypeResolver implements NodeTypeResolverInterface
{
    private NodeTypeResolver $nodeTypeResolver;

    public function __construct(
        private BetterNodeFinder $betterNodeFinder
    ) {
    }

    #[Required]
    public function autowireClassMethodOrClassConstTypeResolver(NodeTypeResolver $nodeTypeResolver): void
    {
        $this->nodeTypeResolver = $nodeTypeResolver;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeClasses(): array
    {
        return [ClassMethod::class, ClassConst::class];
    }

    /**
     * @param ClassMethod|ClassConst $node
     */
    public function resolve(Node $node, \PHPStan\Analyser\Scope $scope): Type
    {
        $classLike = $this->betterNodeFinder->findParentType($node, ClassLike::class);
        if (! $classLike instanceof ClassLike) {
            // anonymous class
            return new ObjectWithoutClassType();
        }

        return $this->nodeTypeResolver->getType($classLike);
    }
}
