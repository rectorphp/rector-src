<?php

declare(strict_types=1);

namespace Rector\Php74\NodeConverter;

use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use Rector\Php74\NodeAnalyzer\ClosureArrowFunctionAnalyzer;

final readonly class ClosureToArrowFunctionConverter
{
    public function __construct(
        private ClosureArrowFunctionAnalyzer $closureArrowFunctionAnalyzer,
    ) {
    }

    public function convert(Closure $closure): ?ArrowFunction
    {
        if (! (version_compare(PHP_VERSION, '7.4.0') >= 0)) {
            return null;
        }

        $expression = $this->closureArrowFunctionAnalyzer->matchArrowFunctionExpr($closure);
        if ($expression === null) {
            return null;
        }

        return new ArrowFunction([
            'expr' => $expression,
            'returnType' => $closure->returnType,
            'byRef' => $closure->byRef,
            'params' => $closure->params,
            'attrGroups' => $closure->attrGroups,
            'static' => $closure->static,
        ]);
    }
}
