<?php

namespace Rector\Tests\PHPUnit\Rector\Class_\RemoveDataProviderTestPrefixRector\Fixture;

class BaseClass extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider testProvideData()
     */
    public function test()
    {
        $nothing = 5;
    }

}

namespace Rector\Tests\PHPUnit\Rector\Class_\RemoveDataProviderTestPrefixRector\Fixture;

class ChildClass extends BaseClass
{

    public function testProvideData()
    {
        return ['123'];
    }
    
}

?>
-----
<?php

namespace Rector\Tests\PHPUnit\Rector\Class_\RemoveDataProviderTestPrefixRector\Fixture;

class BaseClass extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test()
    {
        $nothing = 5;
    }

}

namespace Rector\Tests\PHPUnit\Rector\Class_\RemoveDataProviderTestPrefixRector\Fixture;

class ChildClass extends BaseClass
{

    public function provideData()
    {
        return ['123'];
    }
    
}

?>
