<?php

namespace Rector\Tests\Renaming\Rector\PropertyFetch\RenamePropertyRector\Fixture;

class MyClass
{
	protected static array $_config = ['tmp'];

    public static function test() {
       $tmp = self::$_config;
       return static::$_config;
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\PropertyFetch\RenamePropertyRector\Fixture;

class MyClass
{
	protected static array $config = ['tmp'];

    public static function test() {
       $tmp = self::$config;
       return static::$config;
    }
}

?>
