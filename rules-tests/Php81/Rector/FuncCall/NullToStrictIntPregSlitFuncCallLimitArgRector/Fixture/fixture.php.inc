<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictIntPregSlitFuncCallLimitArgRector\Fixture;

class Fixture
{
    public function run($output)
    {
        preg_split('/\s/', $output, NULL, PREG_SPLIT_NO_EMPTY);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictIntPregSlitFuncCallLimitArgRector\Fixture;

class Fixture
{
    public function run($output)
    {
        preg_split('/\s/', $output, 0, PREG_SPLIT_NO_EMPTY);
    }
}

?>
