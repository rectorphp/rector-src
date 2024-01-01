<?php

declare(strict_types=1);

namespace Rector\NodeAnalyzer;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use Rector\DeadCode\NodeManipulator\VariadicFunctionLikeDetector;
use Rector\PhpParser\AstResolver;
use Rector\Reflection\ReflectionResolver;

final class VariadicAnalyzer
{
    public function __construct(
        private readonly AstResolver $astResolver,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly VariadicFunctionLikeDetector $variadicFunctionLikeDetector
    ) {
    }

    public function hasVariadicParameters(FuncCall | StaticCall | MethodCall $call): bool
    {
        $functionLikeReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($call);
        if ($functionLikeReflection === null) {
            return false;
        }

        if ($this->hasVariadicVariant($functionLikeReflection)) {
            return true;
        }

        if ($functionLikeReflection instanceof FunctionReflection) {
            $function = $this->astResolver->resolveFunctionFromFunctionReflection($functionLikeReflection);
            if (! $function instanceof Function_) {
                return false;
            }

            return $this->variadicFunctionLikeDetector->isVariadic($function);
        }

        return false;
    }

    private function hasVariadicVariant(MethodReflection | FunctionReflection $functionLikeReflection): bool
    {
        foreach ($functionLikeReflection->getVariants() as $parametersAcceptor) {
            // can be any number of arguments → nothing to limit here
            if ($parametersAcceptor->isVariadic()) {
                return true;
            }
        }

        return false;
    }
}
