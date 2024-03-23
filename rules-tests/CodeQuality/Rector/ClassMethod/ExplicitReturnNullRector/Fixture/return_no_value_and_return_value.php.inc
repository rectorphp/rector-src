<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class ReturnNoValueAndReturnValue
{
    /**
     * @return int|void
     */
    public function run(int $number)
    {
        if ($number > 50) {
            return;
        }

        if ($number < 50) {
            return 100;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class ReturnNoValueAndReturnValue
{
    /**
     * @return int|null
     */
    public function run(int $number)
    {
        if ($number > 50) {
            return null;
        }

        if ($number < 50) {
            return 100;
        }
        return null;
    }
}

?>