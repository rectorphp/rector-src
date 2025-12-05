<?php

declare(strict_types=1);

namespace Rector\NodeAnalyzer;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\Closure;
use PHPStan\Reflection\ParameterReflectionWithPhpDocs;
use Rector\NodeTypeResolver\PHPStan\ParametersAcceptorSelectorVariantsWrapper;
use Rector\PHPStan\ScopeFetcher;
use Rector\Reflection\ReflectionResolver;

class CallLikeExpectsThisBindedClosureArgsAnalyzer
{
    public function __construct(
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    /**
     * @param CallLike $callLike
     * @return Arg[]
     * @throws \Rector\Exception\ShouldNotHappenException
     */
    public function getArgsUsingThisBindedClosure(CallLike $callLike): array
    {
        $args = [];
        $reflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($callLike);

        if ($callLike->isFirstClassCallable()) {
            return [];
        }

        if ($reflection === null) {
            return [];
        }

        $scope = ScopeFetcher::fetch($callLike);

        $parametersAcceptor = ParametersAcceptorSelectorVariantsWrapper::select($reflection, $callLike, $scope);
        $parameters = $parametersAcceptor->getParameters();

        foreach ($callLike->getArgs() as $index => $arg) {

            if (! $arg->value instanceof Closure) {
                continue;
            }

            if ($arg->name?->name !== null) {
                foreach ($parameters as $parameter) {
                    $hasObjectBinding = (bool) $parameter->getClosureThisType();
                    if ($hasObjectBinding && $arg->name->name === $parameter->getName()) {
                        $args[] = $arg;
                    }
                }

                continue;
            }

            if (! is_string($arg->name?->name)) {
                $parameter = $parameters[$index] ?? null;

                if ($parameter === null) {
                    continue;
                }

                if ($parameter->getClosureThisType() !== null) {
                    $args[] = $arg;
                }
            }
        }

        return $args;
    }
}
