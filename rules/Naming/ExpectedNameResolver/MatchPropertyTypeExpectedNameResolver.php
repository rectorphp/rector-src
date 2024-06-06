<?php

declare(strict_types=1);

namespace Rector\Naming\ExpectedNameResolver;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ObjectType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Naming\Naming\PropertyNaming;
use Rector\Naming\ValueObject\ExpectedName;
use Rector\NodeManipulator\PropertyManipulator;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\Reflection\ReflectionResolver;
use Rector\StaticTypeMapper\StaticTypeMapper;

final readonly class MatchPropertyTypeExpectedNameResolver
{
    public function __construct(
        private PropertyNaming $propertyNaming,
        private PhpDocInfoFactory $phpDocInfoFactory,
        private NodeNameResolver $nodeNameResolver,
        private PropertyManipulator $propertyManipulator,
        private ReflectionResolver $reflectionResolver,
        private StaticTypeMapper $staticTypeMapper
    ) {
    }

    public function resolve(Property $property, ClassLike $classLike): ?string
    {
        if (! $classLike instanceof Class_) {
            return null;
        }

        $classReflection = $this->reflectionResolver->resolveClassReflection($property);
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        $propertyName = $this->nodeNameResolver->getName($property);
        if ($this->propertyManipulator->isUsedByTrait($classReflection, $propertyName)) {
            return null;
        }

        $expectedName = $this->resolveExpectedName($property);
        if (! $expectedName instanceof ExpectedName) {
            return null;
        }

        // skip if already has suffix
        if (str_ends_with($propertyName, $expectedName->getName()) || str_ends_with(
            $propertyName,
            ucfirst($expectedName->getName())
        )) {
            return null;
        }

        return $expectedName->getName();
    }

    private function resolveExpectedName(Property $property): ?ExpectedName
    {
        // property type first
        if ($property->type instanceof Node) {
            $propertyType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($property->type);
            return $this->propertyNaming->getExpectedNameFromType($propertyType);
        }

        // fallback to docblock
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($property);
        $isVarTypeObjectType = $phpDocInfo instanceof PhpDocInfo && $phpDocInfo->getVarType() instanceof ObjectType;
        if ($isVarTypeObjectType) {
            return $this->propertyNaming->getExpectedNameFromType($phpDocInfo->getVarType());
        }

        return null;
    }
}
