<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnExprInConstructRector\Fixture;

final class ReturnDynamicExprInConstruct
{
    public function __construct()
    {
        if (rand(0, 1)) {
            return parent::__construct();
            echo 'dead code, ensure stmt inserted between';
        }

        $this->execute();
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnExprInConstructRector\Fixture;

final class ReturnDynamicExprInConstruct
{
    public function __construct()
    {
        if (rand(0, 1)) {
            parent::__construct();
            return;
            echo 'dead code, ensure stmt inserted between';
        }

        $this->execute();
    }
}

?>
