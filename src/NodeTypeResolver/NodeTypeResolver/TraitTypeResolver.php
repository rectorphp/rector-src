<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\NodeTypeResolver;

use PhpParser\Node;
use PhpParser\Node\Stmt\Trait_;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\NodeTypeResolver\Contract\NodeTypeResolverInterface;

/**
 * @see \Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\TraitTypeResolver\TraitTypeResolverTest
 *
 * @implements NodeTypeResolverInterface<Trait_>
 */
final readonly class TraitTypeResolver implements NodeTypeResolverInterface
{
    public function __construct(
        private ReflectionProvider $reflectionProvider
    ) {
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeClasses(): array
    {
        return [Trait_::class];
    }

    /**
     * @param Trait_ $node
     */
    public function resolve(Node $node): Type
    {
        $traitName = (string) $node->namespacedName;
        if (! $this->reflectionProvider->hasClass($traitName)) {
            return new MixedType();
        }

        $classReflection = $this->reflectionProvider->getClass($traitName);
        if ($classReflection->getTraits() === []) {
            return new ObjectType($traitName);
        }

        $types = [new ObjectType($traitName)];

        foreach ($classReflection->getTraits() as $usedTraitReflection) {
            $types[] = new ObjectType($usedTraitReflection->getName());
        }

        return new UnionType($types);
    }
}
