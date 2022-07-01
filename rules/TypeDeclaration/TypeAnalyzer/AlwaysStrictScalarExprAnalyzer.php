<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\MagicConst;
use PhpParser\Node\Scalar\MagicConst\Line;
use PhpParser\Node\Scalar\String_;
use PHPStan\Reflection\Native\NativeFunctionReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\BooleanType;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\NullType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;

final class AlwaysStrictScalarExprAnalyzer
{
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

    public function matchStrictScalarExpr(Expr $expr): ?Type
    {
        if ($expr instanceof Scalar) {
            return $this->resolveTypeFromScalar($expr);
        }

        if ($expr instanceof ConstFetch) {
            $name = $expr->name->toLowerString();
            if ($name === 'null') {
                return new NullType();
            }

            if (in_array($name, ['true', 'false'], true)) {
                return new BooleanType();
            }

            return null;
        }

        if ($expr instanceof FuncCall) {
            return $this->resolveFuncCallType($expr);
        }

        return null;
    }

    private function resolveTypeFromScalar(Scalar $scalar): Type|null
    {
        if ($scalar instanceof String_) {
            return new StringType();
        }

        if ($scalar instanceof DNumber) {
            return new FloatType();
        }

        if ($scalar instanceof LNumber) {
            return new IntegerType();
        }

        if ($scalar instanceof Line) {
            return new IntegerType();
        }

        if ($scalar instanceof MagicConst) {
            return new StringType();
        }

        return null;
    }

    private function resolveFuncCallType(FuncCall $funcCall): ?Type
    {
        if (! $funcCall->name instanceof Name) {
            return null;
        }

        if (! $this->reflectionProvider->hasFunction($funcCall->name, null)) {
            return null;
        }

        $functionReflection = $this->reflectionProvider->getFunction($funcCall->name, null);
        if (! $functionReflection instanceof NativeFunctionReflection) {
            return null;
        }

        $parametersAcceptor = ParametersAcceptorSelector::selectSingle($functionReflection->getVariants());
        return $parametersAcceptor->getReturnType();
    }
}
