<?php

declare(strict_types=1);

namespace Rector\DowngradePhp80\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ConstFetch;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\Reflection\ParametersAcceptor;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\DowngradePhp80\Reflection\DefaultParameterValueResolver;
use Rector\NodeNameResolver\NodeNameResolver;
use PHPStan\Reflection\Native\NativeFunctionReflection;
use ReflectionFunction;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use Rector\Core\PhpParser\Node\NodeFactory;

final class UnnamedArgumentResolver
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private ValueResolver $valueResolver,
        private DefaultParameterValueResolver $defaultParameterValueResolver,
        private NodeFactory $nodeFactory
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
        $parametersAcceptor         = ParametersAcceptorSelector::selectSingle($functionLikeReflection->getVariants());
        $unnamedArgs                = [];
        $parameters                 = $parametersAcceptor->getParameters();
        $isNativeFunctionReflection = $functionLikeReflection instanceof NativeFunctionReflection;

        if ($isNativeFunctionReflection) {
            $functionLikeReflection = new ReflectionFunction($functionLikeReflection->getName());
        }

        /** @var Arg[] $unnamedArgs */
        $unnamedArgs  = [];
        $toFillArgs   = [];
        foreach ($currentArgs as $key => $arg) {
            if ($arg->name === null) {
                $unnamedArgs[$key] = new Arg(
                    $arg->value,
                    $arg->byRef,
                    $arg->unpack,
                    $arg->getAttributes(),
                    null
                );

                continue;
            }

            $toFillArgs[] = $this->nodeNameResolver->getName($arg->name);
        }

        foreach ($parameters as $paramPosition => $parameterReflection) {
            $parameterReflectionName = $parameterReflection->getName();
            if (in_array($parameterReflectionName, $toFillArgs, true)) {
                foreach ($currentArgs as $key => $arg) {
                    if ($arg->name instanceof Identifier && $this->nodeNameResolver->isName($arg->name, $parameterReflectionName)) {
                        $unnamedArgs[$paramPosition] = new Arg(
                            $arg->value,
                            $arg->byRef,
                            $arg->unpack,
                            $arg->getAttributes(),
                            null
                        );
                    }
                }
            }
        }

        $keys = array_keys($unnamedArgs);
        for ($i = 0; $i < count($parameters); $i++) {
            if (! in_array($i, $keys, true)) {
                $parameterReflection = $isNativeFunctionReflection
                    ? $functionLikeReflection->getParameters()[$i]
                    : $parameters[$i];

                $unnamedArgs[$i] = new Arg(
                    $isNativeFunctionReflection
                        ? ($parameterReflection->getDefaultValue() === null
                             ? $this->nodeFactory->createNull()
                             : $this->nodeFactory->createConstFetch((string) $parameterReflection->getDefaultValue()))
                        : $this->defaultParameterValueResolver->resolveFromParameterReflection($parameterReflection)
                    ,
                    false,
                    false,
                    [],
                    null
                );
            }
        }

        ksort($unnamedArgs);
        return $unnamedArgs;
    }

    private function shouldSkipParam(Arg $arg, ParameterReflection $parameterReflection): bool
    {
        if (! $this->nodeNameResolver->isName($arg, $parameterReflection->getName())) {
            return true;
        }

        return $this->areArgValueAndParameterDefaultValueEqual($parameterReflection, $arg);
    }

    private function areArgValueAndParameterDefaultValueEqual(ParameterReflection $parameterReflection, Arg $arg): bool
    {
        // arg value vs parameter default value
        if ($parameterReflection->getDefaultValue() === null) {
            return false;
        }

        $defaultValue = $this->defaultParameterValueResolver->resolveFromParameterReflection($parameterReflection);

        // default value is set already, let's skip it
        return $this->valueResolver->isValue($arg->value, $defaultValue);
    }

    /**
     * @param Arg[] $unnamedArgs
     * @return Arg[]
     */
    private function fillArgValues(
        int $highestParameterPosition,
        array $unnamedArgs,
        MethodReflection | FunctionReflection $functionLikeReflection
    ): array {
        // fill parameter default values
        for ($i = 0; $i < $highestParameterPosition; ++$i) {
            // the argument is already set, no need to override it
            if (isset($unnamedArgs[$i])) {
                continue;
            }

            $defaultExpr = $this->defaultParameterValueResolver->resolveFromFunctionLikeAndPosition(
                $functionLikeReflection,
                $i
            );

            if (! $defaultExpr instanceof Expr) {
                continue;
            }

            $unnamedArgs[$i] = new Arg($defaultExpr);
        }

        return $unnamedArgs;
    }
}
