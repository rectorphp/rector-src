<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class DifferentNamed
{
    public function run()
    {
        preg_split('#a#', null, flags: 0);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class DifferentNamed
{
    public function run()
    {
        preg_split('#a#', '', flags: 0);
    }
}

?>
