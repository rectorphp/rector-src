<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeCollector;

use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\NodeAnalyzer\ParamAnalyzer;

final class UnusedParameterResolver
{
    public function __construct(
        private readonly ParamAnalyzer $paramAnalyzer
    ) {
    }

    /**
     * @return array<int, Param>
     */
    public function resolve(ClassMethod $classMethod): array
    {
        /** @var array<int, Param> $unusedParameters */
        $unusedParameters = [];

        foreach ($classMethod->params as $i => $param) {
            // skip property promotion
            /** @var Param $param */
            if ($param->flags !== 0) {
                continue;
            }

            if ($this->paramAnalyzer->isParamUsedInClassMethod($classMethod, $param)) {
                continue;
            }

            $unusedParameters[$i] = $param;
        }

        return $unusedParameters;
    }
}
