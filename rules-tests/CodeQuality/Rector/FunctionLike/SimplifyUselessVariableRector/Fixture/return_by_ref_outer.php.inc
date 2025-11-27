<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector\Fixture;

class ReturnByRefOther
{
    function & test()
    {
        function foo() {
            $var = 9000;
            return $var;
        }

        $var = 9000;
        return $var;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector\Fixture;

class ReturnByRefOther
{
    function & test()
    {
        function foo() {
            return 9000;
        }

        $var = 9000;
        return $var;
    }
}

?>
