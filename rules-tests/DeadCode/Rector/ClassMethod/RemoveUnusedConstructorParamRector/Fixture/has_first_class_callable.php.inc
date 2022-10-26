<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector\Fixture;

final class HasFirstClassCallable
{
    private $hey;

    public function __construct($hey, $man)
    {
        $this->hey = $hey;

        $this->execute(...);
    }

    private function execute()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector\Fixture;

final class HasFirstClassCallable
{
    private $hey;

    public function __construct($hey)
    {
        $this->hey = $hey;

        $this->execute(...);
    }

    private function execute()
    {
    }
}

?>
