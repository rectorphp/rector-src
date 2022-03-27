<?php

namespace Rector\Tests\Php74\Rector\FuncCall\GetCalledClassToStaticClassRector\Fixture;

final class Fixture
{
    public function callOnMe()
    {
        var_dump( get_called_class());
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\FuncCall\GetCalledClassToStaticClassRector\Fixture;

final class Fixture
{
    public function callOnMe()
    {
        var_dump( self::class);
    }
}

?>
