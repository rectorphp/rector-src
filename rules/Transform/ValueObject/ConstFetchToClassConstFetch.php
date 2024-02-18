<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

use Rector\Validation\RectorAssert;

final readonly class ConstFetchToClassConstFetch
{
    public function __construct(private string $oldConstName, private string $newClassName, private string $newConstName)
    {
        RectorAssert::constantName($this->oldConstName);
        RectorAssert::className($this->newClassName);
        RectorAssert::constantName($this->newConstName);
    }

    public function getOldConstName(): string
    {
        return $this->oldConstName;
    }

    public function getNewClassName(): string
    {
        return $this->newClassName;
    }

    public function getNewConstName(): string
    {
        return $this->newConstName;
    }
}
