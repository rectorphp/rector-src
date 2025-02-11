<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector\Fixture;

final class DoubleStaticPrivateMethod
{
    public function output()
    {
        $result = self::run();
    }

    private static function run()
    {
        self::someStatic();
    }

    private static function someStatic()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector\Fixture;

final class DoubleStaticPrivateMethod
{
    public function output()
    {
        $result = $this->run();
    }

    private function run()
    {
        $this->someStatic();
    }

    private function someStatic()
    {
    }
}

?>
