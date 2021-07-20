<?php

declare(strict_types=1);

namespace Rector\DowngradePhp80\NodeAnalyzer;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Identifier;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\Native\NativeFunctionReflection;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Reflection\Php\PhpParameterReflection;
use Rector\DowngradePhp80\Reflection\DefaultParameterValueResolver;
use Rector\NodeNameResolver\NodeNameResolver;
use ReflectionFunction;

final class UnnamedArgumentResolver
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private DefaultParameterValueResolver $defaultParameterValueResolver
    ) {
    }

    /**
     * @param Arg[] $currentArgs
     * @return Arg[]
     */
    public function resolveFromReflection(
        FunctionReflection | MethodReflection $functionLikeReflection,
        array $currentArgs
    ): array {
        $parametersAcceptor = ParametersAcceptorSelector::selectSingle($functionLikeReflection->getVariants());
        $unnamedArgs = [];
        $parameters = $parametersAcceptor->getParameters();
        $isNativeFunctionReflection = $functionLikeReflection instanceof NativeFunctionReflection;

        if ($isNativeFunctionReflection) {
            $functionLikeReflection = new ReflectionFunction($functionLikeReflection->getName());
        }

        /** @var Arg[] $unnamedArgs */
        $unnamedArgs = [];
        $toFillArgs = [];
        foreach ($currentArgs as $key => $arg) {
            if ($arg->name === null) {
                $unnamedArgs[$key] = new Arg($arg->value, $arg->byRef, $arg->unpack, $arg->getAttributes(), null);

                continue;
            }

            /** @var string $argName */
            $argName = $this->nodeNameResolver->getName($arg->name);
            $toFillArgs[] = $argName;
        }

        $unnamedArgs = $this->fillFromNamedArgs($parameters, $currentArgs, $toFillArgs, $unnamedArgs);
        $unnamedArgs = $this->fillFromJumpedNamedArgs(
            $functionLikeReflection,
            $unnamedArgs,
            $isNativeFunctionReflection,
            $parameters
        );
        ksort($unnamedArgs);
        return $unnamedArgs;
    }

    /**
     * @param ParameterReflection[]|PhpParameterReflection[] $parameters
     * @param Arg[] $currentArgs
     * @param string[] $toFillArgs
     * @param Arg[] $unnamedArgs
     * @return Arg[]
     */
    private function fillFromNamedArgs(
        array $parameters,
        array $currentArgs,
        array $toFillArgs,
        array $unnamedArgs
    ): array {
        foreach ($parameters as $paramPosition => $parameterReflection) {
            $parameterReflectionName = $parameterReflection->getName();
            if (! in_array($parameterReflectionName, $toFillArgs, true)) {
                continue;
            }

            foreach ($currentArgs as $currentArg) {
                if (! $currentArg->name instanceof Identifier) {
                    continue;
                }

                if (! $this->nodeNameResolver->isName($currentArg->name, $parameterReflectionName)) {
                    continue;
                }

                $unnamedArgs[$paramPosition] = new Arg(
                    $currentArg->value,
                    $currentArg->byRef,
                    $currentArg->unpack,
                    $currentArg->getAttributes(),
                    null
                );
            }
        }

        return $unnamedArgs;
    }

    /**
     * @param Arg[] $unnamedArgs
     * @param ParameterReflection[]|PhpParameterReflection[] $parameters
     * @return Arg[]
     */
    private function fillFromJumpedNamedArgs(
        FunctionReflection | MethodReflection | ReflectionFunction $functionLikeReflection,
        array $unnamedArgs,
        bool $isNativeFunctionReflection,
        array $parameters
    ): array {
        $keys = array_keys($unnamedArgs);
        $highestParameterPosition = max($keys ?: [0]);
        $parametersCount = count($parameters);
        for ($i = 0; $i < $parametersCount; ++$i) {
            if (in_array($i, $keys, true) || $i > $highestParameterPosition) {
                continue;
            }

            /** @var ParameterReflection|PhpParameterReflection $parameterReflection */
            if ($isNativeFunctionReflection) {
                /** @var ReflectionFunction $functionLikeReflection */
                $parameterReflection = new PhpParameterReflection(
                    $functionLikeReflection->getParameters()[$i],
                    null,
                    null
                );
            } else {
                $parameterReflection = $parameters[$i];
            }

            $defaulValue = $this->defaultParameterValueResolver->resolveFromParameterReflection(
                $parameterReflection
            );

            if (! $defaulValue instanceof Expr) {
                continue;
            }

            $unnamedArgs[$i] = new Arg(
                $defaulValue,
                $parameterReflection->passedByReference()
                    ->yes(),
                $parameterReflection->isVariadic(),
                [],
                null
            );
        }

        return $unnamedArgs;
    }
}
