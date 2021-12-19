<?php

namespace Rector\Tests\DowngradePhp74\Rector\Property\DowngradeTypedPropertyRector\Fixture;

class Foo
{

    private Foo|Bar|false $prop;

}
?>
-----
<?php

namespace Rector\Tests\DowngradePhp74\Rector\Property\DowngradeTypedPropertyRector\Fixture;

class Foo
{

    /**
     * @var false|\Rector\Tests\DowngradePhp74\Rector\Property\DowngradeTypedPropertyRector\Fixture\Bar|\Rector\Tests\DowngradePhp74\Rector\Property\DowngradeTypedPropertyRector\Fixture\Foo
     */
    private $prop;

}
?>
