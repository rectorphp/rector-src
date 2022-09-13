<?php

namespace Rector\Tests\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector\Fixture;

final class DemoFile
{
    /**
     * @var Redis
     */
    public static $foo_bar;
    public function run()
    {
        $lala = self::$foo_bar->multi();
    }
}

?>
-----
<?php

namespace Rector\Tests\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector\Fixture;

final class DemoFile
{
    /**
     * @var Redis
     */
    public static $foo_bar;
    public function run()
    {
        $multi = self::$foo_bar->multi();
    }
}

?>
