<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeAnalyzer;

use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Scalar;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionVariantWithPhpDocs;
use PHPStan\Type\MixedType;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\NodeAnalyzer\TypeNodeUnwrapper;

final class ReturnStrictTypeAnalyzer
{
    public function __construct(
        private readonly ReflectionResolver $reflectionResolver,
        private readonly TypeNodeUnwrapper $typeNodeUnwrapper,
        private readonly StaticTypeMapper $staticTypeMapper
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

            $returnedStrictTypeNodes[] = $returnNode;
        }

        if (! $containsStrictCall) {
            return [];
        }

        return $this->typeNodeUnwrapper->uniquateNodes($returnedStrictTypeNodes);
    }

    public function resolveMethodCallReturnNode(MethodCall | StaticCall | FuncCall $call): ?Node
    {
        $methodReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($call);
        if ($methodReflection === null) {
            return null;
        }

        $parametersAcceptor = $methodReflection->getVariants()[0];
        if ($parametersAcceptor instanceof FunctionVariantWithPhpDocs) {
            // native return type is needed, as docblock can be false
            $returnType = $parametersAcceptor->getNativeReturnType();
        } else {
            $returnType = $parametersAcceptor->getReturnType();
        }

        if ($returnType instanceof MixedType) {
            return null;
        }

        return $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($returnType, TypeKind::RETURN);
    }

    private function resolveLiteralReturnNode(Array_|Scalar $returnedExpr, Scope $scope): ?Node
    {
        $returnType = $scope->getType($returnedExpr);
        return $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($returnType, TypeKind::RETURN);
    }
}
