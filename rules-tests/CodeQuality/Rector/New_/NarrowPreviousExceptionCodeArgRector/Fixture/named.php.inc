<?php

namespace Rector\Tests\CodeQuality\Rector\New_\NarrowPreviousExceptionCodeArgRector\Fixture;

final class Named
{
    public function run()
    {
        try {
            $this->run();
        } catch (\Throwable $throwable) {
            throw new \RuntimeException(message: 'Failed', code: $throwable->getCode(), previous: $throwable);
        }
    }

    public function mixed()
    {
        try {
            $this->run();
        } catch (\Throwable $throwable) {
            throw new \RuntimeException('Failed', $throwable->getCode(), previous: $throwable);
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\New_\NarrowPreviousExceptionCodeArgRector\Fixture;

final class Named
{
    public function run()
    {
        try {
            $this->run();
        } catch (\Throwable $throwable) {
            throw new \RuntimeException(message: 'Failed', previous: $throwable);
        }
    }

    public function mixed()
    {
        try {
            $this->run();
        } catch (\Throwable $throwable) {
            throw new \RuntimeException('Failed', previous: $throwable);
        }
    }
}

?>
