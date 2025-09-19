<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

class MixedFromUnserialize
{
    public function run(string $param)
    {
        $this->doStuff(unserialize($param));
    }

    private function doStuff(array $value)
    {
        // do stuff with value
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

class MixedFromUnserialize
{
    public function run(string $param)
    {
        $this->doStuff(unserialize($param));
    }

    /**
     * @param mixed[] $value
     */
    private function doStuff(array $value)
    {
        // do stuff with value
    }
}

?>
