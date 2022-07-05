<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\Fixture;

final class Foo {
    private callable $callable;
    public function setCallable(callable $callable) {
        $this->callable = $callable;
    }
}
namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\Fixture;

final class DemoFile
{
    public function run()
    {
        $foo = new Foo();
        $foo->setCallable($this->test(...));
    }
    
    private function test($foo, $bar) {}
}

$demo = new DemoFile();
$demo->run();

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\Fixture;

// what is expected code?
// should remain the same? delete part bellow ----- (included)

?>
