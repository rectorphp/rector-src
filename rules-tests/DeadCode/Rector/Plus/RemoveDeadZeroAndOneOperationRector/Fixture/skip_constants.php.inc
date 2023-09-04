<?php

namespace Rector\Tests\DeadCode\Rector\Plus\RemoveDeadZeroAndOneOperationRector\Fixture;

final class SkipConstants
{
    public CONST DAYS_TO_DELAY = 1;
    
    public function run()
    {
        return self::DAYS_TO_DELAY * 3600;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Plus\RemoveDeadZeroAndOneOperationRector\Fixture;

final class SkipConstants
{
    public CONST DAYS_TO_DELAY = 1;
    
    public function run()
    {
        return self::DAYS_TO_DELAY * 3600;
    }
}

?>
