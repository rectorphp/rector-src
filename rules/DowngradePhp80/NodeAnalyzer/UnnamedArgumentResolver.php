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
                    $isNativeFunctionReflection ? $parameterReflection->isPassedByReference() : $parameterReflection->passedByReference()->yes(),
                    $isNativeFunctionReflection ? $parameterReflection->isVariadic() : $parameterReflection->isVariadic(),
                    [],
                    null
                );
            }
        }

        ksort($unnamedArgs);
        return $unnamedArgs;
    }
}
