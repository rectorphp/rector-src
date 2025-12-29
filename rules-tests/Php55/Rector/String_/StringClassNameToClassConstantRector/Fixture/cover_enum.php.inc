<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class CoverEnum
{
    public function run()
    {
        $enumClass = 'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\SomeEnum';
    }
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class CoverEnum
{
    public function run()
    {
        $enumClass = \Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\SomeEnum::class;
    }
}

?>
