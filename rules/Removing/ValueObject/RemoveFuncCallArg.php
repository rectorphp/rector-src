<?php

declare(strict_types=1);

namespace Rector\Removing\ValueObject;

use Rector\Validation\RectorAssert;

final readonly class RemoveFuncCallArg
{
    public function __construct(
        private string $function,
        private int $argumentPosition
    ) {
        RectorAssert::functionName($function);
    }

    public function getFunction(): string
    {
        return $this->function;
    }

    public function getArgumentPosition(): int
    {
        return $this->argumentPosition;
    }
}
