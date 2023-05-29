<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector\Fixture;

final class MultipleUnusedParams
{
    private $hey;

    public function __construct($hey, $man, $thisIsMe)
    {
        $this->hey = $hey;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector\Fixture;

final class MultipleUnusedParams
{
    private $hey;

    public function __construct($hey)
    {
        $this->hey = $hey;
    }
}

?>
