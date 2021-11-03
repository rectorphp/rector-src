<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\NodeTypeResolver;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\Core\Enum\ObjectReference;
use Rector\NodeTypeResolver\Contract\NodeTypeResolverInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * @see \Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\NameTypeResolver\NameTypeResolverTest
 */
final class NameTypeResolver implements NodeTypeResolverInterface
{
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
    public function resolve(Node $node, \PHPStan\Analyser\Scope $scope): Type
    {
        if ($node->toString() === ObjectReference::PARENT()->getValue()) {
            return $this->resolveParentReference($scope);
        }

        $fullyQualifiedName = $this->resolveFullyQualifiedName($node, $scope);

        if ($node->toString() === 'array') {
            return new ArrayType(new MixedType(), new MixedType());
        }

        return new ObjectType($fullyQualifiedName);
    }

    private function resolveParentReference(Scope $scope): MixedType | ObjectType | UnionType
    {
        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
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

    private function resolveFullyQualifiedName(Name $name, Scope $scope): string
    {
        $nameValue = $name->toString();
        if (in_array(
            $nameValue,
            [ObjectReference::SELF()->getValue(), ObjectReference::STATIC()->getValue(), 'this'],
            true
        )) {
            $classReflection = $scope->getClassReflection();
            if ($classReflection->isAnonymous()) {
                return 'Anonymous';
            }

            return $classReflection->getName();
        }

        /** @var Name|null $resolvedNameNode */
        $resolvedNameNode = $name->getAttribute(AttributeKey::RESOLVED_NAME);
        if ($resolvedNameNode instanceof Name) {
            return $resolvedNameNode->toString();
        }

        return $nameValue;
    }
}
