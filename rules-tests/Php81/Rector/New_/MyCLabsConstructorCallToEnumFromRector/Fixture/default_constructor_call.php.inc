<?php

namespace Rector\Tests\Php81\Rector\New_\MyCLabsConstructorCallToEnumFromRector\Fixture;

use Rector\Tests\Php81\Rector\New_\MyCLabsConstructorCallToEnumFromRector\Source\SomeEnum;

class RefactorDefaultConstructorCall
{
    public function someMethod(): SomeEnum
    {
        return new SomeEnum('value');
    }
}
?>
-----
<?php

namespace Rector\Tests\Php81\Rector\New_\MyCLabsConstructorCallToEnumFromRector\Fixture;

use Rector\Tests\Php81\Rector\New_\MyCLabsConstructorCallToEnumFromRector\Source\SomeEnum;

class RefactorDefaultConstructorCall
{
    public function someMethod(): SomeEnum
    {
        return \Rector\Tests\Php81\Rector\New_\MyCLabsConstructorCallToEnumFromRector\Source\SomeEnum::from('value');
    }
}
?>
