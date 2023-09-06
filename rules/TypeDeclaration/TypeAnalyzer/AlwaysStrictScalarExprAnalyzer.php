<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\Encapsed;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\MagicConst;
use PhpParser\Node\Scalar\MagicConst\Line;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\Native\NativeFunctionReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\BooleanType;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\NullType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\NodeTypeResolver\PHPStan\ParametersAcceptorSelectorVariantsWrapper;

final class AlwaysStrictScalarExprAnalyzer
{
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
        private readonly NodeTypeResolver $nodeTypeResolver
    ) {
    }

    public function matchStrictScalarExpr(Expr $expr, Scope $scope): ?Type
    {
        if ($expr instanceof Concat) {
            return new StringType();
        }

        if ($expr instanceof Cast) {
            return $this->resolveCastType($expr);
        }

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
            return $this->resolveFuncCallType($expr, $scope);
        }

        $exprType = $this->nodeTypeResolver->getNativeType($expr);
        if ($exprType->isScalar()->yes()) {
            return $exprType;
        }

        return null;
    }

    private function resolveCastType(Cast $cast): ?Type
    {
        $type = $this->nodeTypeResolver->getNativeType($cast);
        if ($type->isScalar()->yes()) {
            return $type;
        }

        return null;
    }

    private function resolveTypeFromScalar(Scalar $scalar): Type|null
    {
        if ($scalar instanceof Encapsed) {
            return new StringType();
        }

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

    private function resolveFuncCallType(FuncCall $funcCall, Scope $scope): ?Type
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

        $parametersAcceptor = ParametersAcceptorSelectorVariantsWrapper::select(
            $functionReflection,
            $funcCall,
            $scope
        );

        $returnType = $parametersAcceptor->getReturnType();
        if ($returnType->isScalar()->yes()) {
            return $returnType;
        }

        return null;
    }
}
