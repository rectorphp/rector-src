<?php

namespace Rector\Tests\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector\WithoutInheritedCode\Fixture;

use RuntimeException;
use Throwable;

final class AddPreviousNamedArgument
{
    public function run(): void
    {
        try {
            $this->doSomething();
        } catch (Throwable $throwable) {
            throw new RuntimeException('Failed');
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector\WithoutInheritedCode\Fixture;

use RuntimeException;
use Throwable;

final class AddPreviousNamedArgument
{
    public function run(): void
    {
        try {
            $this->doSomething();
        } catch (Throwable $throwable) {
            throw new RuntimeException('Failed', previous: $throwable);
        }
    }
}

?>
