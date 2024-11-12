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
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Webmozart\Assert\Assert;

final readonly class AnnotationToAttributeIntegerValueCaster
{
    public function __construct(
        private ReflectionProvider $reflectionProvider,
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

                // ensure type is casted to integer
                if (! $arrayItem->value instanceof String_) {
                    continue;
                }

                if (! $this->containsInteger($parameterReflection->getType())) {
                    continue;
                }

                $valueString = $arrayItem->value;
                if (! is_numeric($valueString->value)) {
                    continue;
                }

                $arrayItem->value = new LNumber((int) $valueString->value);
            }
        }
    }

    private function containsInteger(Type $type): bool
    {
        if ($type->isInteger()->yes()) {
            return true;
        }

        if (! $type instanceof UnionType) {
            return false;
        }

        foreach ($type->getTypes() as $unionedType) {
            if ($unionedType->isInteger()->yes()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return ParameterReflection[]
     */
    private function resolveConstructorParameterReflections(ClassReflection $classReflection): array
    {
        $extendedMethodReflection = $classReflection->getConstructor();

        $parametersAcceptorWithPhpDocs = ParametersAcceptorSelector::combineAcceptors(
            $extendedMethodReflection->getVariants()
        );

        return $parametersAcceptorWithPhpDocs->getParameters();
    }
}
