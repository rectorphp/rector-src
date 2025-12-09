<?php

declare(strict_types=1);

namespace Rector\NodeAnalyzer;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\Closure;
use PHPStan\Reflection\ExtendedParameterReflection;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\ParametersAcceptorSelectorVariantsWrapper;
use Rector\Reflection\ReflectionResolver;

final readonly class CallLikeExpectsThisBindedClosureArgsAnalyzer
{
    public function __construct(
        private ReflectionResolver $reflectionResolver
    ) {
    }

    /**
     * @return Arg[]
     */
    public function getArgsUsingThisBindedClosure(CallLike $callLike): array
    {
        $args = [];
        $reflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($callLike);

        if ($callLike->isFirstClassCallable() || $callLike->getArgs() === []) {
            return [];
        }

        if ($reflection === null) {
            return [];
        }

        $scope = $callLike->getAttribute(AttributeKey::SCOPE);

        if ($scope === null) {
            return [];
        }

        $parametersAcceptor = ParametersAcceptorSelectorVariantsWrapper::select($reflection, $callLike, $scope);
        $parameters = $parametersAcceptor->getParameters();

        foreach ($callLike->getArgs() as $index => $arg) {
            if (! $arg->value instanceof Closure) {
                continue;
            }

            if ($arg->name?->name !== null) {
                foreach ($parameters as $parameter) {
                    if (! $parameter instanceof ExtendedParameterReflection) {
                        continue;
                    }

                    $hasObjectBinding = (bool) $parameter->getClosureThisType();
                    if ($hasObjectBinding && $arg->name->name === $parameter->getName()) {
                        $args[] = $arg;
                    }
                }

                continue;
            }

            if (! is_string($arg->name?->name)) {
                $parameter = $parameters[$index] ?? null;

                if (! $parameter instanceof ExtendedParameterReflection) {
                    continue;
                }

                $hasObjectBinding = (bool) $parameter->getClosureThisType();
                if ($hasObjectBinding) {
                    $args[] = $arg;
                }
            }
        }

        return $args;
    }
}
