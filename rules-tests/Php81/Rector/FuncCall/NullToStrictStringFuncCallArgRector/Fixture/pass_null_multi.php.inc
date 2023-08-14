<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class PassNullMulti
{
    public function run()
    {
        str_contains(null, null);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class PassNullMulti
{
    public function run()
    {
        str_contains('', '');
    }
}

?>
