<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\NodeTypeResolver;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Interface_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\NodeTypeResolver\Contract\NodeTypeResolverInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;
use PHPStan\Type\ObjectWithoutClassType;
use Rector\NodeNameResolver\NodeNameResolver;

/**
 * @see \Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\ClassAndInterfaceTypeResolver\ClassTypeResolverTest
 * @see \Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\ClassAndInterfaceTypeResolver\InterfaceTypeResolverTest
 *
 * @implements NodeTypeResolverInterface<Class_|Interface_>
 */
final class ClassAndInterfaceTypeResolver implements NodeTypeResolverInterface
{
    public function __construct(private NodeNameResolver $nodeNameResolver)
    {
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeClasses(): array
    {
        return [Class_::class, Interface_::class];
    }

    /**
     * @param Class_|Interface_ $node
     */
    public function resolve(Node $node): Type
    {
        $scope = $node->getAttribute(AttributeKey::SCOPE);

        if (! $scope instanceof Scope) {
            // new node probably
            return new MixedType();
        }

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            $objectType = new ObjectType((string) $this->nodeNameResolver->getName($node));
            return new ObjectWithoutClassType($objectType);
        }

        return new ObjectType($classReflection->getName(), null, $classReflection);
    }
}
