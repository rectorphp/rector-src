<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\ChildPopulator;

use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Type;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\PhpParser\AstResolver;
use Rector\NodeCollector\NodeCollector\NodeRepository;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PHPStanStaticTypeMapper\ValueObject\TypeKind;
use Rector\TypeDeclaration\NodeTypeAnalyzer\ChildTypeResolver;

final class ChildReturnPopulator
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private NodeRepository $nodeRepository,
        private ChildTypeResolver $childTypeResolver,
        private ReflectionProvider $reflectionProvider,
        private AstResolver $astResolver
    ) {
    }

    /**
     * Add typehint to all children class methods
     */
    public function populateChildren(ClassMethod $classMethod, Type $returnType): void
    {
        $className = $classMethod->getAttribute(AttributeKey::CLASS_NAME);
        if (! is_string($className)) {
            throw new ShouldNotHappenException();
        }

        $childrenClassLikes = $this->nodeRepository->findChildrenOfClass($className);
        if ($childrenClassLikes === []) {
            return;
        }

        // update their methods as well
        foreach ($childrenClassLikes as $childClassLike) {
            $className = (string) $this->nodeNameResolver->getName($childClassLike);
            if (! $this->reflectionProvider->hasClass($className)) {
                $usedTraits = $this->nodeRepository->findUsedTraitsInClass($childClassLike);
            } else {
                $classReflection = $this->reflectionProvider->getClass($className);
                $usedTraits = $this->astResolver->parseClassReflectionTraits($classReflection);
            }

            foreach ($usedTraits as $usedTrait) {
                $this->addReturnTypeToChildMethod($usedTrait, $classMethod, $returnType);
            }

            $this->addReturnTypeToChildMethod($childClassLike, $classMethod, $returnType);
        }
    }

    private function addReturnTypeToChildMethod(
        ClassLike $classLike,
        ClassMethod $classMethod,
        Type $returnType
    ): void {
        $methodName = $this->nodeNameResolver->getName($classMethod);

        $currentClassMethod = $classLike->getMethod($methodName);
        if (! $currentClassMethod instanceof ClassMethod) {
            return;
        }

        $resolvedChildTypeNode = $this->childTypeResolver->resolveChildTypeNode($returnType, TypeKind::RETURN());
        if ($resolvedChildTypeNode === null) {
            return;
        }

        $currentClassMethod->returnType = $resolvedChildTypeNode;

        // make sure the type is not overridden
        $currentClassMethod->returnType->setAttribute(AttributeKey::DO_NOT_CHANGE, true);
    }
}
