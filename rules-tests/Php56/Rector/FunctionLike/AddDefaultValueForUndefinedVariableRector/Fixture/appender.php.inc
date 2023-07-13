<?php

namespace Rector\Tests\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector\Fixture;

class Appender
{
    public function run($a = 1)
    {
        return $a += $b;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector\Fixture;

class Appender
{
    public function run($a = 1)
    {
        $b = null;
        return $a += $b;
    }
}

?>
