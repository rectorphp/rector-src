<?php

namespace Rector\Tests\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector\WithoutInheritedCode\Fixture;

use RuntimeException;
use Throwable;

final class SkipExplicitCodeAndExistingPrevious
{
    public function keepExplicitCode(): void
    {
        try {
            $this->doSomething();
        } catch (Throwable $e) {
            throw new RuntimeException('Not found', 404, $e);
        }
    }

    public function skipNonCapturingCatch(): void
    {
        try {
            $this->doSomething();
        } catch (Throwable) {
            throw new RuntimeException('Failed');
        }
    }

    public function skipAlreadyNamedPrevious(): void
    {
        try {
            $this->doSomething();
        } catch (Throwable $e) {
            throw new RuntimeException('Failed', previous: $e);
        }
    }
}
