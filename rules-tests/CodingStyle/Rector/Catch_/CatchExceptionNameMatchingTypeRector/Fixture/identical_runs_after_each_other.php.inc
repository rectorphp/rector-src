<?php

namespace Rector\Tests\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector\Source\Notification;
use Throwable;

final class IdenticalRunsAfterEachOther
{
    public function run()
    {
        try {
        } catch (Throwable $e) {
            $notification = new Notification();
            $notification->exception($e);
        }

        try {
        } catch (Throwable $e) {
            $notification = new Notification();
            $notification->exception($e);
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector\Source\Notification;
use Throwable;

final class IdenticalRunsAfterEachOther
{
    public function run()
    {
        try {
        } catch (Throwable $throwable) {
            $notification = new Notification();
            $notification->exception($throwable);
        }

        try {
        } catch (Throwable $throwable) {
            $notification = new Notification();
            $notification->exception($throwable);
        }
    }
}

?>
