<?php

namespace Rector\Tests\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector\Fixture;

class InitializedJumpNext
{
    public function run()
    {
        if (rand(0, 1)) {
            echo $a;
        }

        $a = null;

        if (rand(0, 1)) {
            $a = 5;
        } else if (rand(0,1)) {
            unset($a);
        }

        echo $a;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector\Fixture;

class InitializedJumpNext
{
    public function run()
    {
        $a = null;
        if (rand(0, 1)) {
            echo $a;
        }

        $a = null;

        if (rand(0, 1)) {
            $a = 5;
        } else if (rand(0,1)) {
            unset($a);
        }

        echo $a;
    }
}

?>