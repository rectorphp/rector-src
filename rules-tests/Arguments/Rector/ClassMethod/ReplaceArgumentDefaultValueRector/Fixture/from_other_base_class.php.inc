<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Fixture;

class FromOtherBaseClass
{
    public const PATH = 'path';

    public static function test1(string $path)
    {
        \Mage::getStoreConfig(self::PATH);
    }
}

?>
-----
<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Fixture;

class FromOtherBaseClass
{
    public const PATH = 'path';

    public static function test1(string $path)
    {
        \Mage::getStoreConfig(\Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Fixture\SomeBase::PATH);
    }
}

?>