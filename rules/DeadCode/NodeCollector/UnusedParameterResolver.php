<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeCollector;

use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\NodeAnalyzer\ParamAnalyzer;

final readonly class UnusedParameterResolver
{
    public function __construct(
        private ParamAnalyzer $paramAnalyzer
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
            if ($param->isPromoted()) {
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
