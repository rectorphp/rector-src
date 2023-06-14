<?php

namespace Rector\Tests\RemovingStatic\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector\Fixture;

class MethodWithArg
{
    public function run($arg)
    {
        self::someStatic($arg);
    }

    private static function someStatic($arg)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\RemovingStatic\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector\Fixture;

class MethodWithArg
{
    public function run($arg)
    {
        $this->someStatic($arg);
    }

    private function someStatic($arg)
    {
    }
}

?>
