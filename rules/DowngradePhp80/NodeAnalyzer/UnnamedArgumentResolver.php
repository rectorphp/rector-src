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

        foreach ($parameters as $paramPosition => $parameterReflection) {
            $parameterReflectionName = $parameterReflection->getName();
            foreach ($currentArgs as $key => $currentArg) {
                if ($key === $paramPosition) {
                    if (! $currentArg->name instanceof Identifier || $this->nodeNameResolver->isName($currentArg->name, $parameterReflectionName)) {
                        $unnamedArgs[$paramPosition] = new Arg(
                            $currentArg->value,
                            $currentArg->byRef,
                            $currentArg->unpack,
                            $currentArg->getAttributes(),
                            null
                        );

                        continue;
                    }

                    if ($isNativeFunctionReflection) {
                        $currentPosition = $functionLikeReflection->getParameters()[$paramPosition];
                        $unnamedArgs[$paramPosition] = new Arg(
                            $this->nodeFactory->createConstFetch(
                                (string) $currentPosition->getDefaultValue()
                            ),
                            $currentPosition->isPassedByReference(),
                            $currentPosition->isVariadic(),
                            [],
                            null
                        );

                        continue;
                    } else {
                        $unnamedArgs[$paramPosition] = new Arg(
                            $this->defaultParameterValueResolver->resolveFromParameterReflection($parameterReflection),
                            false,
                            false,
                            [],
                            null
                        );
                        continue;
                    }
                }
            }
        }

        $existingNames = [];
        foreach ($currentArgs as $key => $currentArg) {
            if ($currentArg->name instanceof Identifier) {
                $existingNames[$key] = $this->nodeNameResolver->getName($currentArg->name);
            }
        }

        $filledNames = [];
        foreach ($parameters as $paramPosition => $parameterReflection) {
            foreach (array_keys($unnamedArgs) as $value) {
                if ($paramPosition === $value) {
                    $filledNames[] = $parameterReflection->getName();
                }
            }
        }

        $appends = [];
        foreach ($existingNames as $key => $existingName) {
            if (! in_array($existingName, $filledNames, true)) {
                $appends[$key] = $existingName;
            }
        }

        dump($appends);

        foreach ($appends as $key => $append) {
            foreach ($parameters as $paramPosition => $parameterReflection) {
                if ($parameterReflection->getName() === $append) {
                    $unnamedArgs[$paramPosition] = $currentArgs[$key];
                }
            }
        }

        return $unnamedArgs;
    }

    private function shouldSkipParam(Arg $arg, ParameterReflection $parameterReflection): bool
    {
        if ($arg->name === null) {
            return false;
        }

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
