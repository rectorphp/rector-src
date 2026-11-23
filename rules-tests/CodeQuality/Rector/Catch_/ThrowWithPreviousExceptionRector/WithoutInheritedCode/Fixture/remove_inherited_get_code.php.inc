<?php

namespace Rector\Tests\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector\WithoutInheritedCode\Fixture;

use RuntimeException;
use Throwable;

final class RemoveInheritedGetCode
{
    public function positionalBoth(): void
    {
        try {
            $this->doSomething();
        } catch (Throwable $e) {
            throw new RuntimeException('Failed', $e->getCode(), $e);
        }
    }

    public function positionalCodeOnly(): void
    {
        try {
            $this->doSomething();
        } catch (Throwable $e) {
            throw new RuntimeException('Failed', $e->getCode());
        }
    }

    public function namedArguments(): void
    {
        try {
            $this->doSomething();
        } catch (Throwable $e) {
            throw new RuntimeException('Failed', code: $e->getCode(), previous: $e);
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector\WithoutInheritedCode\Fixture;

use RuntimeException;
use Throwable;

final class RemoveInheritedGetCode
{
    public function positionalBoth(): void
    {
        try {
            $this->doSomething();
        } catch (Throwable $e) {
            throw new RuntimeException('Failed', previous: $e);
        }
    }

    public function positionalCodeOnly(): void
    {
        try {
            $this->doSomething();
        } catch (Throwable $e) {
            throw new RuntimeException('Failed', previous: $e);
        }
    }

    public function namedArguments(): void
    {
        try {
            $this->doSomething();
        } catch (Throwable $e) {
            throw new RuntimeException('Failed', previous: $e);
        }
    }
}

?>
