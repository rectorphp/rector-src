<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictIntPregSlitFuncCallLimitArgRector\Fixture;

class DifferentNamedArg
{
    public function run($output)
    {
        preg_split('/\s/', $output, NULL, flags: PREG_SPLIT_NO_EMPTY);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictIntPregSlitFuncCallLimitArgRector\Fixture;

class DifferentNamedArg
{
    public function run($output)
    {
        preg_split('/\s/', $output, 0, flags: PREG_SPLIT_NO_EMPTY);
    }
}

?>
