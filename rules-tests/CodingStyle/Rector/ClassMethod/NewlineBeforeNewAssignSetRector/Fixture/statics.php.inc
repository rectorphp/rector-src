<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\NewlineBeforeNewAssignSetRector\Fixture;

final class Statics
{
    protected static ?Value $fooBar = null;
    protected static ?Value $barFoo = null;
    public function run()
    {
        self::$fooBar = new Value;
        self::$fooBar->bar = 1;
        self::$barFoo = new Value;
        self::$barFoo->bar = 1;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\NewlineBeforeNewAssignSetRector\Fixture;

final class Statics
{
    protected static ?Value $fooBar = null;
    protected static ?Value $barFoo = null;
    public function run()
    {
        self::$fooBar = new Value;
        self::$fooBar->bar = 1;

        self::$barFoo = new Value;
        self::$barFoo->bar = 1;
    }
}

?>
