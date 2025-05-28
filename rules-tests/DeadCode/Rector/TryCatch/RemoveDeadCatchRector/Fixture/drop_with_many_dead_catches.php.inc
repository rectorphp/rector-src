<?php

namespace Rector\Tests\DeadCode\Rector\TryCatch\RemoveDeadTryCatchRector\Fixture;

class DropWithManyDeadCatches
{
    public function run()
    {
        try {
            // some code
        } catch (SomeException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        } catch (DeadException $exception) {
            throw $exception;
        } catch (RuntimeException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        } catch (\Throwable $throwable) {
            throw $throwable;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\TryCatch\RemoveDeadTryCatchRector\Fixture;

class DropWithManyDeadCatches
{
    public function run()
    {
        try {
            // some code
        } catch (SomeException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        } catch (RuntimeException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }
}

?>
