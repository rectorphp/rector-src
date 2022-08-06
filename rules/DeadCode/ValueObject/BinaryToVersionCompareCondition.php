<?php

declare(strict_types=1);

namespace Rector\DeadCode\ValueObject;

use Rector\DeadCode\Contract\ConditionInterface;

final class BinaryToVersionCompareCondition implements ConditionInterface
{
    public function __construct(
        private readonly VersionCompareCondition $versionCompareCondition,
        private readonly string $binaryClass,
        private readonly mixed $expectedValue
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

    /**
     * @return mixed
     */
    public function getExpectedValue()
    {
        return $this->expectedValue;
    }
}
