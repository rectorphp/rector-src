<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\IntersectionType;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\UnionType;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\MixedType;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\PhpParser\AstResolver;
use Rector\StaticTypeMapper\StaticTypeMapper;

final readonly class CallerParamMatcher
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private AstResolver $astResolver,
        private StaticTypeMapper $staticTypeMapper,
        private TypeComparator $typeComparator
    ) {
    }

    public function matchCallParamType(
        Param $param,
        Param $callParam
    ): null | Identifier | Name | NullableType | UnionType | ComplexType {
        if (! $callParam->type instanceof Node) {
            return null;
        }

        if (! $param->default instanceof Expr && ! $callParam->default instanceof Expr) {
            // skip as mixed is not helpful and possibly requires more precise change elsewhere
            if ($this->isCallParamMixed($callParam->type)) {
                return null;
            }

            return $callParam->type;
        }

        $default = $param->default ?? $callParam->default;
        if (! $default instanceof Expr) {
            return null;
        }

        $callParamType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($callParam->type);
        $defaultType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($default);

        if ($this->typeComparator->areTypesEqual($callParamType, $defaultType)) {
            return $callParam->type;
        }

        if ($this->typeComparator->isSubtype($defaultType, $callParamType)) {
            return $callParam->type;
        }

        if (! $defaultType->isNull()->yes()) {
            return null;
        }

        if ($callParam->type instanceof Name || $callParam->type instanceof Identifier) {
            return new NullableType($callParam->type);
        }

        if ($callParam->type instanceof IntersectionType || $callParam->type instanceof UnionType) {
            return new UnionType([...$callParam->type->types, new Identifier('null')]);
        }

        return null;
    }

    public function matchParentParam(StaticCall $parentStaticCall, Param $param, Scope $scope): ?Param
    {
        $methodName = $this->nodeNameResolver->getName($parentStaticCall->name);
        if ($methodName === null) {
            return null;
        }

        // match current param to parent call position
        $parentStaticCallArgPosition = $this->matchCallArgPosition($parentStaticCall, $param);
        if ($parentStaticCallArgPosition === null) {
            return null;
        }

        return $this->resolveParentMethodParam($scope, $methodName, $parentStaticCallArgPosition);
    }

    public function matchCallParam(StaticCall | MethodCall | FuncCall $call, Param $param, Scope $scope): ?Param
    {
        $callArgPosition = $this->matchCallArgPosition($call, $param);
        if ($callArgPosition === null) {
            return null;
        }

        $classMethodOrFunction = $this->astResolver->resolveClassMethodOrFunctionFromCall($call, $scope);
        if ($classMethodOrFunction === null) {
            return null;
        }

        return $classMethodOrFunction->params[$callArgPosition] ?? null;
    }

    private function matchCallArgPosition(StaticCall | MethodCall | FuncCall $call, Param $param): int | null
    {
        $paramName = $this->nodeNameResolver->getName($param);

        foreach ($call->args as $argPosition => $arg) {
            if (! $arg instanceof Arg) {
                continue;
            }

            if (! $arg->value instanceof Variable) {
                continue;
            }

            if (! $this->nodeNameResolver->isName($arg->value, $paramName)) {
                continue;
            }

            return $argPosition;
        }

        return null;
    }

    private function resolveParentMethodParam(Scope $scope, string $methodName, int $paramPosition): ?Param
    {
        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        foreach ($classReflection->getParents() as $parentClassReflection) {
            if (! $parentClassReflection->hasMethod($methodName)) {
                continue;
            }

            $parentClassMethod = $this->astResolver->resolveClassMethod($parentClassReflection->getName(), $methodName);
            if (! $parentClassMethod instanceof ClassMethod) {
                continue;
            }

            return $parentClassMethod->params[$paramPosition] ?? null;
        }

        return null;
    }

    private function isCallParamMixed(Node $node): bool
    {
        $callParamType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($node);
        return $callParamType instanceof MixedType;
    }
}
