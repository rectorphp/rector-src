<?php

namespace Rector\Tests\Php73\Rector\BinaryOr\IsCountableRector\Fixture;

class OnMethodCall
{
    public function go($foo)
    {
        is_array($foo->execute()) || $foo->execute() instanceof \Countable;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php73\Rector\BinaryOr\IsCountableRector\Fixture;

class OnMethodCall
{
    public function go($foo)
    {
        is_countable($foo->execute());
    }
}

?>
