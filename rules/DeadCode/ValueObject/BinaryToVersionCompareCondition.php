<?php

declare(strict_types=1);

namespace Rector\DeadCode\ValueObject;

use Rector\DeadCode\Contract\ConditionInterface;

final readonly class BinaryToVersionCompareCondition implements ConditionInterface
{
    public function __construct(
        private VersionCompareCondition $versionCompareCondition,
        private string $binaryClass,
        private mixed $expectedValue
    ) {
    }

    public function getVersionCompareCondition(): VersionCompareCondition
    {
        return $this->versionCompareCondition;
    }

    public function getBinaryClass(): string
    {
        return $this->binaryClass;
    }

    public function getExpectedValue(): mixed
    {
        return $this->expectedValue;
    }
}
