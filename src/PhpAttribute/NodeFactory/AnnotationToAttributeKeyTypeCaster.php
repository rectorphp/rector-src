<?php

declare(strict_types=1);

namespace Rector\PhpAttribute\NodeFactory;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\IntegerType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\PHPStanStaticTypeMapper\Utils\TypeUnwrapper;
use Webmozart\Assert\Assert;

final readonly class AnnotationToAttributeKeyTypeCaster
{
    public function __construct(
        private ReflectionProvider $reflectionProvider,
        private TypeUnwrapper $typeUnwrapper,
    ) {
    }

    /**
     * @param Arg[] $args
     */
    public function castAttributeTypes(AnnotationToAttribute $annotationToAttribute, array $args): void
    {
        Assert::allIsInstanceOf($args, Arg::class);

        if (! $this->reflectionProvider->hasClass($annotationToAttribute->getAttributeClass())) {
            return;
        }

        $attributeClassReflection = $this->reflectionProvider->getClass($annotationToAttribute->getAttributeClass());
        if (! $attributeClassReflection->hasConstructor()) {
            return;
        }

        $parameterReflections = $this->resolveConstructorParameterReflections($attributeClassReflection);

        foreach ($parameterReflections as $parameterReflection) {
            foreach ($args as $arg) {
                if (! $arg->value instanceof ArrayItem) {
                    continue;
                }

                $arrayItem = $arg->value;
                if (! $arrayItem->key instanceof String_) {
                    continue;
                }

                $keyString = $arrayItem->key;
                if ($keyString->value !== $parameterReflection->getName()) {
                    continue;
                }

                if (! $this->isNullableIntegerOrIntegerType($parameterReflection->getType())) {
                    continue;
                }

                // ensure type is casted to integer
                if (! $arrayItem->value instanceof String_) {
                    continue;
                }

                $arrayItem->value = new LNumber((int) $arrayItem->value->value);
            }
        }
    }

    private function isNullableIntegerOrIntegerType(Type $type): bool
    {
        if ($type instanceof IntegerType) {
            return true;
        }

        if (! $type instanceof UnionType) {
            return false;
        }

        $unwrappedType = $this->typeUnwrapper->removeNullTypeFromUnionType($type);
        return $unwrappedType instanceof IntegerType;
    }

    /**
     * @return ParameterReflection[]
     */
    private function resolveConstructorParameterReflections(ClassReflection $classReflection): array
    {
        $extendedMethodReflection = $classReflection->getConstructor();

        $parametersAcceptorWithPhpDocs = ParametersAcceptorSelector::selectSingle(
            $extendedMethodReflection->getVariants()
        );

        return $parametersAcceptorWithPhpDocs->getParameters();
    }
}
