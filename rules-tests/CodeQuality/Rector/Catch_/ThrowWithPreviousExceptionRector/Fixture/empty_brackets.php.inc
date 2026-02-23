<?php

namespace Rector\Tests\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector\Fixture;

class EmptyBrackets
{
    public function run()
    {
        try {
            $someCode = 1;
        } catch (\Throwable $throwable) {
            throw new \RuntimeException;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector\Fixture;

class EmptyBrackets
{
    public function run()
    {
        try {
            $someCode = 1;
        } catch (\Throwable $throwable) {
            throw new \RuntimeException($throwable->getMessage(), $throwable->getCode(), $throwable);
        }
    }
}

?>
