<?php

declare(strict_types=1);

namespace Rector\DowngradePhp80\NodeAnalyzer;

use PhpParser\Node\Arg;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\Reflection\ParametersAcceptor;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\DeadCode\Comparator\Parameter\ParameterDefaultsComparator;
use Rector\DowngradePhp80\Reflection\DefaultParameterValueResolver;
use Rector\NodeNameResolver\NodeNameResolver;

final class UnnamedArgumentResolver
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private ParameterDefaultsComparator $parameterDefaultsComparator,
        private ValueResolver $valueResolver,
        private DefaultParameterValueResolver $defaultParameterValueResolver
    ) {
    }

    /**
     * @param FunctionReflection|MethodReflection $functionLikeReflection
     * @param Arg[] $currentArgs
     * @return Arg[]
     */
    public function resolveFromReflection(
        FunctionReflection | MethodReflection $functionLikeReflection,
        array $currentArgs
    ) {
        $parametersAcceptor = $functionLikeReflection->getVariants()[0] ?? null;
        if (! $parametersAcceptor instanceof ParametersAcceptor) {
            return [];
        }

        $unnamedArgs = [];

        foreach ($parametersAcceptor->getParameters() as $paramPosition => $parameterReflection) {
            foreach ($currentArgs as $currentArg) {
                if ($this->shouldSkipParam($currentArg, $parameterReflection)) {
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

        $highestParameterPosition = max(array_keys($unnamedArgs));

        // fill parameter default values
        for ($i = 0; $i < $highestParameterPosition; ++$i) {
            if (isset($unnamedArgs[$i])) {
                continue;
            }

            $defaultValue = $this->defaultParameterValueResolver->resolveFromFunctionLikeAndPosition(
                $functionLikeReflection,
                $i
            );
            dump($defaultValue);
            die;
        }

        dump($highestParameterPosition);
        die;

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
}
