<?php

declare(strict_types=1);

namespace Rector\Removing\ValueObject;

use Rector\Core\Validation\RectorAssert;

final class RemoveFuncCall
{
    public function __construct(private readonly string $function)
    {
        RectorAssert::functionName($function);
    }

    public function getFunction(): string
    {
        return $this->function;
    }
}
