<?php

namespace Rector\Tests\DeadCode\Rector\TryCatch\RemoveDeadCatchRector\Fixture;

class DropWithManyDeadCatches
{
    public function run()
    {
        try {
            // some code
        } catch (\RangeException $exception) {
            throw new \InvalidArgumentException($exception->getMessage());
        } catch (\DomainException $exception) {
            throw $exception;
        } catch (\RuntimeException $exception) {
            throw new \InvalidArgumentException($exception->getMessage());
        } catch (\Throwable $throwable) {
            throw $throwable;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\TryCatch\RemoveDeadCatchRector\Fixture;

class DropWithManyDeadCatches
{
    public function run()
    {
        try {
            // some code
        } catch (\RangeException $exception) {
            throw new \InvalidArgumentException($exception->getMessage());
        } catch (\RuntimeException $exception) {
            throw new \InvalidArgumentException($exception->getMessage());
        }
    }
}

?>
