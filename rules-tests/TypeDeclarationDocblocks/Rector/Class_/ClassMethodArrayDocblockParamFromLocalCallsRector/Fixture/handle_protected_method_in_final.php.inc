<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class HandleProtectedMethodInFinal
{
    public function go()
    {
        $this->run([2512, 3423]);

        $this->run([324, 534]);
    }

    protected function run(array $items)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class HandleProtectedMethodInFinal
{
    public function go()
    {
        $this->run([2512, 3423]);

        $this->run([324, 534]);
    }

    /**
     * @param int[] $items
     */
    protected function run(array $items)
    {
    }
}

?>
