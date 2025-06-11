<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveArgumentFromDefaultParentCallRector\Fixture;

use Exception;

final class MultipleDefault extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message, 0, null);
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveArgumentFromDefaultParentCallRector\Fixture;

use Exception;

final class MultipleDefault extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}

?>
