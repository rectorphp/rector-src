<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture\NamedArgs;

final class FlippedArgs
{
    public function go()
    {
        $this->run(names: ['John', 'Doe'], items: [123, 456]);
    }

    private function run(array $items, array $names)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture\NamedArgs;

final class FlippedArgs
{
    public function go()
    {
        $this->run(names: ['John', 'Doe'], items: [123, 456]);
    }

    /**
     * @param int[] $items
     * @param string[] $names
     */
    private function run(array $items, array $names)
    {
    }
}

?>
