<?php

namespace Rector\Tests\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector\Fixture;

class Fixture
{
    public function run()
    {
        try {
            $someCode = 1;
        } catch (\Throwable $throwable) {
            throw new \RuntimeException('ups');
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector\Fixture;

class Fixture
{
    public function run()
    {
        try {
            $someCode = 1;
        } catch (\Throwable $throwable) {
            throw new \RuntimeException('ups', $throwable->getCode(), $throwable);
        }
    }
}

?>
