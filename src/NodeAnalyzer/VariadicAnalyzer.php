<?php

declare(strict_types=1);

namespace Rector\NodeAnalyzer;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptor;
use Rector\Reflection\ReflectionResolver;

final readonly class VariadicAnalyzer
{
    public function __construct(
        private ReflectionResolver $reflectionResolver
    ) {
    }

    public function hasVariadicParameters(FuncCall | StaticCall | MethodCall | New_ $call): bool
    {
        $functionLikeReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($call);
        if ($functionLikeReflection === null) {
            return false;
        }

        return $this->hasVariadicVariant($functionLikeReflection);
    }

    private function hasVariadicVariant(MethodReflection | FunctionReflection $functionLikeReflection): bool
    {
        return array_any(
            $functionLikeReflection->getVariants(),
<<<<<<< HEAD
            fn (ParametersAcceptor $parametersAcceptor): bool => $parametersAcceptor->isVariadic()
=======
            fn ($parametersAcceptor) => $parametersAcceptor->isVariadic()
>>>>>>> 424f600506 ([php] bump to PHP 8.4 syntax)
        );
    }
}
