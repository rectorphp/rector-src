<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeAnalyzer\ReturnFilter;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use Rector\Core\Reflection\ReflectionResolver;

final class ExclusiveNativeCallLikeReturnMatcher
{
    public function __construct(
        private readonly ReflectionResolver $reflectionResolver,
    ) {
    }

    /**
     * @param Return_[] $returns
     * @return array<StaticCall|FuncCall|MethodCall>|null
     */
    public function match(array $returns): array|null
    {
        $callLikes = [];

        foreach ($returns as $return) {
            // we need exact expr return
            $returnExpr = $return->expr;
            if (! $returnExpr instanceof StaticCall && ! $returnExpr instanceof MethodCall && ! $returnExpr instanceof FuncCall) {
                return null;
            }

            $functionLikeReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($returnExpr);

            if (! $functionLikeReflection instanceof FunctionReflection && ! $functionLikeReflection instanceof MethodReflection) {
                return null;
            }

            // is native func call?
            if (! $this->isNativeCallLike($functionLikeReflection)) {
                return null;
            }

            $callLikes[] = $returnExpr;
        }

        return $callLikes;
    }

    private function isNativeCallLike(MethodReflection|FunctionReflection $functionLikeReflection): bool
    {
        if ($functionLikeReflection instanceof FunctionReflection) {
            return $functionLikeReflection->isBuiltin();
        }

        // is native method call?
        $classReflection = $functionLikeReflection->getDeclaringClass();
        return $classReflection->isBuiltin();
    }
}
