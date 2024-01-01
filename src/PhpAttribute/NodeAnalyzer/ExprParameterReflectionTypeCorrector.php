<?php

declare(strict_types=1);

namespace Rector\PhpAttribute\NodeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\TypeCombinator;
use Rector\PhpParser\Node\NodeFactory;
use Rector\StaticTypeMapper\StaticTypeMapper;

final class ExprParameterReflectionTypeCorrector
{
    public function __construct(
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly ReflectionProvider $reflectionProvider,
        private readonly NodeFactory $nodeFactory
    ) {
    }

    /**
     * @param array<string|int, Expr|mixed> $items
     * @return array<string|int, Expr|mixed>
     */
    public function correctItemsByAttributeClass(array|Array_ $items, string $attributeClass): array
    {
        if ($items instanceof Array_) {
            $items = $items->items;
        }

        if (! $this->reflectionProvider->hasClass($attributeClass)) {
            return $items;
        }

        $attributeClassReflection = $this->reflectionProvider->getClass($attributeClass);

        // nothing to retype by constructor
        if (! $attributeClassReflection->hasConstructor()) {
            return $items;
        }

        $extendedMethodReflection = $attributeClassReflection->getConstructor();

        $parametersAcceptorWithPhpDocs = ParametersAcceptorSelector::selectSingle(
            $extendedMethodReflection->getVariants()
        );

        foreach ($items as $name => $item) {
            foreach ($parametersAcceptorWithPhpDocs->getParameters() as $parameterReflection) {
                $correctedItem = $this->correctItemByParameterReflection($name, $item, $parameterReflection);
                if (! $correctedItem instanceof Expr) {
                    continue;
                }

                $items[$name] = $correctedItem;
                continue 2;
            }
        }

        return $items;
    }

    private function correctItemByParameterReflection(
        string|int $name,
        mixed $item,
        ParameterReflection $parameterReflection,
    ): Expr|null {
        if (! $item instanceof Expr) {
            return null;
        }

        if ($name !== $parameterReflection->getName()) {
            return null;
        }

        $parameterType = $parameterReflection->getType();
        $currentType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($item);

        // all good
        if ($parameterType->accepts($currentType, false)->yes()) {
            return null;
        }

        $clearParameterType = TypeCombinator::removeNull($parameterType);

        // correct type
        if ($clearParameterType->isInteger()->yes() && $item instanceof String_) {
            return new LNumber((int) $item->value);
        }

        if ($clearParameterType->isBoolean()->yes() && $item instanceof String_) {
            if (strtolower($item->value) === 'true') {
                return $this->nodeFactory->createTrue();
            }

            if (strtolower($item->value) === 'false') {
                return $this->nodeFactory->createFalse();
            }
        }

        return null;
    }
}
