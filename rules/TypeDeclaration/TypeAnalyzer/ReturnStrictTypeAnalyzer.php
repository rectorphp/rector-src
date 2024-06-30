<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Scalar;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionVariantWithPhpDocs;
use PHPStan\Type\MixedType;
use PHPStan\Type\StaticType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeTraverser;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\ParametersAcceptorSelectorVariantsWrapper;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Reflection\ReflectionResolver;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Rector\TypeDeclaration\NodeAnalyzer\TypeNodeUnwrapper;

final readonly class ReturnStrictTypeAnalyzer
{
    public function __construct(
        private ReflectionResolver $reflectionResolver,
        private TypeNodeUnwrapper $typeNodeUnwrapper,
        private StaticTypeMapper $staticTypeMapper
    ) {
    }

    /**
     * @param Return_[] $returns
     * @return array<Identifier|Name|NullableType>
     */
    public function collectStrictReturnTypes(array $returns, Scope $scope): array
    {
        $containsStrictCall = false;
        $returnedStrictTypeNodes = [];

        foreach ($returns as $return) {
            if (! $return->expr instanceof Expr) {
                return [];
            }

            $returnedExpr = $return->expr;

            if ($returnedExpr instanceof MethodCall || $returnedExpr instanceof StaticCall || $returnedExpr instanceof FuncCall) {
                $containsStrictCall = true;
                $returnNode = $this->resolveMethodCallReturnNode($returnedExpr);
            } elseif ($returnedExpr instanceof ClassConstFetch) {
                $returnNode = $this->resolveConstFetchReturnNode($returnedExpr, $scope);
            } elseif (
                $returnedExpr instanceof Array_
                || $returnedExpr instanceof String_
                || $returnedExpr instanceof LNumber
                || $returnedExpr instanceof DNumber
            ) {
                $returnNode = $this->resolveLiteralReturnNode($returnedExpr, $scope);
            } else {
                return [];
            }

            if (! $returnNode instanceof Node) {
                return [];
            }

            if ($returnNode instanceof Identifier && $returnNode->toString() === 'void') {
                return [];
            }

            $returnedStrictTypeNodes[] = $returnNode;
        }

        if (! $containsStrictCall) {
            return [];
        }

        return $this->typeNodeUnwrapper->uniquateNodes($returnedStrictTypeNodes);
    }

    public function resolveMethodCallReturnNode(MethodCall | StaticCall | FuncCall $call): ?Node
    {
        $returnType = $this->resolveMethodCallReturnType($call);
        if (! $returnType instanceof Type) {
            return null;
        }

        return $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($returnType, TypeKind::RETURN);
    }

    public function resolveMethodCallReturnType(MethodCall | StaticCall | FuncCall $call): ?Type
    {
        $methodReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($call);
        if ($methodReflection === null) {
            return null;
        }

        $scope = $call->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return null;
        }

        $parametersAcceptorWithPhpDocs = ParametersAcceptorSelectorVariantsWrapper::select(
            $methodReflection,
            $call,
            $scope
        );
        if ($parametersAcceptorWithPhpDocs instanceof FunctionVariantWithPhpDocs) {
            // native return type is needed, as docblock can be false
            $returnType = $parametersAcceptorWithPhpDocs->getNativeReturnType();
        } else {
            $returnType = $parametersAcceptorWithPhpDocs->getReturnType();
        }

        if ($returnType instanceof MixedType) {
            return null;
        }

        return $this->normalizeStaticType($call, $returnType);
    }

    private function normalizeStaticType(MethodCall | StaticCall | FuncCall $call, Type $type): Type
    {
        $reflectionClass = $this->reflectionResolver->resolveClassReflection($call);
        $currentClassName = $reflectionClass instanceof ClassReflection
            ? $reflectionClass->getName()
            : null;

        return TypeTraverser::map($type, static function (Type $currentType, callable $traverseCallback) use (
            $currentClassName
        ): Type {
            if ($currentType instanceof StaticType && $currentClassName !== $currentType->getClassName()) {
                return new FullyQualifiedObjectType($currentType->getClassName());
            }

            return $traverseCallback($currentType);
        });
    }

    private function resolveLiteralReturnNode(Array_|Scalar $returnedExpr, Scope $scope): ?Node
    {
        $returnType = $scope->getType($returnedExpr);
        return $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($returnType, TypeKind::RETURN);
    }

    private function resolveConstFetchReturnNode(ClassConstFetch $classConstFetch, Scope $scope): ?Node
    {
        $constType = $scope->getType($classConstFetch);

        if ($constType instanceof MixedType) {
            return null;
        }

        return $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($constType, TypeKind::RETURN);
    }
}
