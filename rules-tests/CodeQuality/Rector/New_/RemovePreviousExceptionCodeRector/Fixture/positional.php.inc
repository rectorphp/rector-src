<?php

namespace Rector\Tests\CodeQuality\Rector\New_\RemovePreviousExceptionCodeRector\Fixture;

final class Positional
{
    public function run()
    {
        try {
            $this->run();
        } catch (\Throwable $throwable) {
            throw new \RuntimeException('Failed', $throwable->getCode(), $throwable);
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\New_\RemovePreviousExceptionCodeRector\Fixture;

final class Positional
{
    public function run()
    {
        try {
            $this->run();
        } catch (\Throwable $throwable) {
            throw new \RuntimeException('Failed', previous: $throwable);
        }
    }
}

?>
