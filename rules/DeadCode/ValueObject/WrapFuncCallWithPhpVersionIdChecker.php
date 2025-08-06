<?php

namespace Rector\DeadCode\ValueObject;

final readonly class WrapFuncCallWithPhpVersionIdChecker
{
    public function __construct(
        private string $functionName,
        private int $phpVersionId
    ) {
    }

    public function getFunctionName(): string
    {
        return $this->functionName;
    }

    public function getPhpVersionId(): int
    {
        return $this->phpVersionId;
    }
}
