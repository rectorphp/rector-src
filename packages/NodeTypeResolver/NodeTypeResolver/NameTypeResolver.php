<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\NodeTypeResolver;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\Reflection\ClassReflection;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\Core\Enum\ObjectReference;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\NodeTypeResolver\Contract\NodeTypeResolverInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @see \Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\NameTypeResolver\NameTypeResolverTest
 *
 * @implements NodeTypeResolverInterface<Name|FullyQualified>
 */
final class NameTypeResolver implements NodeTypeResolverInterface
{
    private ReflectionResolver $reflectionResolver;

    #[Required]
    public function autowire(ReflectionResolver $reflectionResolver): void
    {
        $this->reflectionResolver = $reflectionResolver;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeClasses(): array
    {
        return [Name::class, FullyQualified::class];
    }

    /**
     * @param Name $node
     */
    public function resolve(Node $node, ?Scope $scope): Type
    {
        if ($node->toString() === ObjectReference::PARENT) {
            return $this->resolveParent($node);
        }

        $fullyQualifiedName = $this->resolveFullyQualifiedName($node);

        if ($node->toString() === 'array') {
            return new ArrayType(new MixedType(), new MixedType());
        }

        return new ObjectType($fullyQualifiedName);
    }

    private function resolveParent(Name $name): MixedType | ObjectType | UnionType
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($name);
        if (! $classReflection instanceof ClassReflection || ! $classReflection->isClass()) {
            return new MixedType();
        }

        if ($classReflection->isAnonymous()) {
            return new MixedType();
        }

        $parentClassObjectTypes = [];
        foreach ($classReflection->getParents() as $parentClassReflection) {
            $parentClassObjectTypes[] = new ObjectType($parentClassReflection->getName());
        }

        if ($parentClassObjectTypes === []) {
            return new MixedType();
        }

        if (count($parentClassObjectTypes) === 1) {
            return $parentClassObjectTypes[0];
        }

        return new UnionType($parentClassObjectTypes);
    }

    private function resolveFullyQualifiedName(Name $name): string
    {
        $nameValue = $name->toString();

        if (in_array($nameValue, [ObjectReference::SELF, ObjectReference::STATIC, 'this'], true)) {
            $classReflection = $this->reflectionResolver->resolveClassReflection($name);
            if (! $classReflection instanceof ClassReflection || $classReflection->isAnonymous()) {
                return $name->toString();
            }

            return $classReflection->getName();
        }

        return $nameValue;
    }
}
