<?php

namespace Rector\NodeAnalyzer;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\Closure;
use Rector\NodeTypeResolver\PHPStan\ParametersAcceptorSelectorVariantsWrapper;
use Rector\PHPStan\ScopeFetcher;
use Rector\Reflection\ReflectionResolver;
use PHPStan\Reflection\ParameterReflectionWithPhpDocs;

class CallLikeExpectsThisBindedClosureArgsAnalyzer
{
    public function __construct(private ReflectionResolver $reflectionResolver)
    {
    }

    public function getArgsUsingThisBindedClosure(CallLike $callLike): array
    {
        /** @var Arg[] $args */
        $args = [];
        $reflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($callLike);

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
                /** @var ParameterReflectionWithPhpDocs $parameter */
                foreach ($parameters as $parameter) {
                    $hasObjectBinding = (bool) $parameter->getClosureThisType();
                    if ($hasObjectBinding && $arg->name->name === $parameter->getName()) {
                        $args[] = $arg;
                    }
                }

                continue;
            }

            if ($arg->name?->name === null) {
                /** @var ParameterReflectionWithPhpDocs $parameter */
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
