<?php

declare(strict_types=1);

namespace Rector\NodeManipulator;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use Rector\PhpParser\Node\Value\ValueResolver;

final readonly class FuncCallManipulator
{
    public function __construct(
        private ValueResolver $valueResolver
    ) {
    }

    /**
     * @param FuncCall[] $compactFuncCalls
     * @return string[]
     */
    public function extractArgumentsFromCompactFuncCalls(array $compactFuncCalls): array
    {
        $arguments = [];
        foreach ($compactFuncCalls as $compactFuncCall) {
            foreach ($compactFuncCall->args as $arg) {
                if (! $arg instanceof Arg) {
                    continue;
                }

                $value = $this->valueResolver->getValue($arg->value);
                if ($value === null) {
                    continue;
                }

                $arguments[] = $value;
            }
        }

        return $arguments;
    }
}
