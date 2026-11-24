<?php

namespace Rector\Tests\CodeQuality\Rector\New_\NarrowPreviousExceptionCodeArgRector\Fixture;

final class SkipDifferentCode
{
    public function run(\Throwable $other)
    {
        try {
            $this->run($other);
        } catch (\Throwable $throwable) {
            throw new \RuntimeException('Failed', 404, $throwable);
        }
    }

    public function otherVariable(\Throwable $other)
    {
        try {
            $this->run($other);
        } catch (\Throwable $throwable) {
            throw new \RuntimeException('Failed', $other->getCode(), $throwable);
        }
    }

    public function missingPrevious(\Throwable $other)
    {
        try {
            $this->run($other);
        } catch (\Throwable $throwable) {
            throw new \RuntimeException('Failed', $throwable->getCode());
        }
    }
}
