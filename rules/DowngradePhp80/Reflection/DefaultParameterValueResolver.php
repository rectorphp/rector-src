<?php

declare(strict_types=1);

namespace Rector\DowngradePhp80\Reflection;

use PhpParser\Node\Expr;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\Reflection\ParametersAcceptor;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\Constant\ConstantFloatType;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\ConstantType;
use PHPStan\Type\NullType;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\StaticTypeMapper\StaticTypeMapper;

final class DefaultParameterValueResolver
{
    public function __construct(
        private StaticTypeMapper $staticTypeMapper
    ) {
    }

    public function resolveFromParameterReflection(ParameterReflection $parameterReflection): \PhpParser\Node | null
    {
        $defaultValue = $parameterReflection->getDefaultValue();
        if ($defaultValue === null) {
            return null;
        }

        return $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($defaultValue);
//
//        if (! $defaultValue instanceof ConstantType) {
//            throw new ShouldNotHappenException();
//        }
//
//        if ($defaultValue instanceof ConstantArrayType) {
//            return $defaultValue->getAllArrays();
//        }
//
//        /** @var ConstantStringType|ConstantIntegerType|ConstantFloatType|ConstantBooleanType|NullType $defaultValue */
//        return $defaultValue->getValue();
    }

    public function resolveFromFunctionLikeAndPosition(
        MethodReflection | FunctionReflection $functionLikeReflection,
        int $position
    ): ?Expr {
        $parametersAcceptor = $functionLikeReflection->getVariants()[0] ?? null;
        if (! $parametersAcceptor instanceof ParametersAcceptor) {
            return null;
        }

        $parameterReflection = $parametersAcceptor->getParameters()[$position] ?? null;
        if ($parameterReflection === null) {
            return null;
        }

        return $this->resolveFromParameterReflection($parameterReflection);
    }
}
