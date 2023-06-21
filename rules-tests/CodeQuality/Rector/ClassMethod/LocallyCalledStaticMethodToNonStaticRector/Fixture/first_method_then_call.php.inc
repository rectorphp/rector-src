<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector\Fixture;

class FirstMethodThenCall
{
    private static function someStatic()
    {
    }

    public function run()
    {
        self::someStatic();
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector\Fixture;

class FirstMethodThenCall
{
    private function someStatic()
    {
    }

    public function run()
    {
        $this->someStatic();
    }
}

?>
