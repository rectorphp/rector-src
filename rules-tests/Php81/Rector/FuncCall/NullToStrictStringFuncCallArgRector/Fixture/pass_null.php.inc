<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class PassNull
{
    public function run()
    {
        preg_split('#a#', null);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class PassNull
{
    public function run()
    {
        preg_split('#a#', '');
    }
}

?>
