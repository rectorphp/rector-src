<?php

namespace Rector\Tests\Php83\Rector\FuncCall\DynamicClassConstFetchRector\Fixture;

class Fixture
{
    public const SOME_CONST = 'some_value';

    public function test()
    {
        $constName = 'SOME_CONST';
        constant(Fixture::class . '::' . $constName);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\FuncCall\DynamicClassConstFetchRector\Fixture;

class Fixture
{
    public const SOME_CONST = 'some_value';

    public function test()
    {
        $constName = 'SOME_CONST';
        Fixture::{$constName};
    }
}

?>
