<?php

namespace Rector\Tests\DowngradePhp72\Rector\ClassMethod\DowngradeParameterTypeWideningRector\Fixture;

interface SomeInterface
{
    public function test(\SplFileInfo $file);
}

abstract class SomeAbstractClass implements SomeInterface
{
    public function test2(\SplFileInfo $file, string $world = 'world') {
        printf('Hello %s', $world);
    }
}

return new class extends SomeAbstractClass {
    public function test(\SplFileInfo $file) {
        $this->test2($file);
    }
};

?>
-----
<?php

namespace Rector\Tests\DowngradePhp72\Rector\ClassMethod\DowngradeParameterTypeWideningRector\Fixture;

interface SomeInterface
{
    /**
     * @param \SplFileInfo $file
     */
    public function test($file);
}

abstract class SomeAbstractClass implements SomeInterface
{
    /**
     * @param \SplFileInfo $file
     * @param string $world
     */
    public function test2($file, $world = 'world') {
        printf('Hello %s', $world);
    }
}

return new class extends SomeAbstractClass {
    /**
     * @param \SplFileInfo $file
     */
    public function test($file) {
        $this->test2($file);
    }
};

?>
