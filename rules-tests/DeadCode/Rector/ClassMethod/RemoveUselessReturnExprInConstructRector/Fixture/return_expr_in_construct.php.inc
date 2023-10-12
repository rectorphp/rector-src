<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnExprInConstructRector\Fixture;

final class ReturnExprInConstruct
{
    public function __construct()
    {
        if (rand(0, 1)) {
            $this->init();
            return true;
        }

        $this->execute();
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnExprInConstructRector\Fixture;

final class ReturnExprInConstruct
{
    public function __construct()
    {
        if (rand(0, 1)) {
            $this->init();
            return;
        }

        $this->execute();
    }
}

?>
