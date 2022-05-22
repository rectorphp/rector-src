<?php

declare(strict_types=1);

namespace Rector\Core\NodeManipulator;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use Rector\Core\PhpParser\Node\Value\ValueResolver;

final class FuncCallManipulator
{
    public function __construct(
        private readonly ValueResolver $valueResolver
    ) {
    }

    /**
     * @return string[]
     */
    public function extractArgumentsFromCompactFuncCall(FuncCall $funcCall): array
    {
        $arguments = [];
        foreach ($funcCall->args as $arg) {
            if (! $arg instanceof Arg) {
                continue;
            }

            $value = $this->valueResolver->getValue($arg->value);
            if ($value === null) {
                continue;
            }

            $arguments[] = $value;
        }

        return $arguments;
    }
}
