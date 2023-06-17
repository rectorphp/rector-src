<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\PhpParser;

use PhpParser\Node;
use PhpParser\Node\Name;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\StaticType;
use PHPStan\Type\StringType;
use PHPStan\Type\ThisType;
use PHPStan\Type\Type;
use Rector\Core\Configuration\RenamedClassesDataCollector;
use Rector\Core\Enum\ObjectReference;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\StaticTypeMapper\Contract\PhpParser\PhpParserNodeMapperInterface;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\ParentObjectWithoutClassType;
use Rector\StaticTypeMapper\ValueObject\Type\ParentStaticType;
use Rector\StaticTypeMapper\ValueObject\Type\SelfStaticType;

/**
 * @implements PhpParserNodeMapperInterface<Name>
 */
final class NameNodeMapper implements PhpParserNodeMapperInterface
{
    public function __construct(
        private readonly RenamedClassesDataCollector $renamedClassesDataCollector,
        private readonly ReflectionProvider $reflectionProvider,
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    public function getNodeType(): string
    {
        return Name::class;
    }

    /**
     * @param Name $node
     */
    public function mapToPHPStan(Node $node): Type
    {
        $name = $node->toString();
        if ($this->isExistingClass($name)) {
            return new FullyQualifiedObjectType($name);
        }

        if (in_array($name, [ObjectReference::STATIC, ObjectReference::SELF, ObjectReference::PARENT], true)) {
            return $this->createClassReferenceType($node, $name);
        }

        return $this->createScalarType($name);
    }

    private function isExistingClass(string $name): bool
    {
        if ($this->reflectionProvider->hasClass($name)) {
            return true;
        }

        // to be existing class names
        $oldToNewClasses = $this->renamedClassesDataCollector->getOldToNewClasses();

        return in_array($name, $oldToNewClasses, true);
    }

    private function createClassReferenceType(
        Name $name,
        string $reference
    ): MixedType | StaticType | SelfStaticType | ObjectWithoutClassType {
        $classReflection = $this->reflectionResolver->resolveClassReflection($name);
        if (! $classReflection instanceof ClassReflection) {
            return new MixedType();
        }

        if ($reference === ObjectReference::STATIC) {
            return new StaticType($classReflection);
        }

        if ($reference === ObjectReference::SELF) {
            return new SelfStaticType($classReflection);
        }

        if ($reference === ObjectReference::PARENT) {
            $parentClassReflection = $classReflection->getParentClass();
            if ($parentClassReflection instanceof ClassReflection) {
                return new ParentStaticType($parentClassReflection);
            }

            return new ParentObjectWithoutClassType();
        }

        return new ThisType($classReflection);
    }

    private function createScalarType(
        string $name
    ): ArrayType | IntegerType | FloatType | StringType | ConstantBooleanType | BooleanType | MixedType {
        if ($name === 'array') {
            return new ArrayType(new MixedType(), new MixedType());
        }

        if ($name === 'int') {
            return new IntegerType();
        }

        if ($name === 'float') {
            return new FloatType();
        }

        if ($name === 'string') {
            return new StringType();
        }

        if ($name === 'false') {
            return new ConstantBooleanType(false);
        }

        if ($name === 'bool') {
            return new BooleanType();
        }

        return new MixedType();
    }
}
