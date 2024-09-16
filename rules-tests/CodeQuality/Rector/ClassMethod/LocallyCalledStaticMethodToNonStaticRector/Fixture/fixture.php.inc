<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector\Fixture;

class Fixture
{
    public function run()
    {
        self::someStatic();
        static::someStatic();
        Fixture::someStatic();
        \Rector\Tests\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector\Fixture\Fixture::someStatic();
    }

    private static function someStatic()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector\Fixture;

class Fixture
{
    public function run()
    {
        $this->someStatic();
        $this->someStatic();
        $this->someStatic();
        $this->someStatic();
    }

    private function someStatic()
    {
    }
}

?>
