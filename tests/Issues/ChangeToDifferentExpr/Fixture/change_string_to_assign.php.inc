<?php

namespace Rector\Tests\Issues\ChangeToDifferentExpr\Fixture;

final class ChangeStringToAssign
{
    public function run()
    {
        echo "test";
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\ChangeToDifferentExpr\Fixture;

final class ChangeStringToAssign
{
    public function run()
    {
        echo $test = $foo;
    }
}

?>
