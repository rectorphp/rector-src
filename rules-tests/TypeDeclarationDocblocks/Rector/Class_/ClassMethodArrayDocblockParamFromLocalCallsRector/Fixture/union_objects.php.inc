<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Source\AnotherReturnedObject;
use Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Source\SomeReturnedObject;

final class UnionObjects
{
    public function go()
    {
        $this->run($this->getUnion());
    }

    private function run(array $items)
    {
    }

    /**
     * @return SomeReturnedObject[]|AnotherReturnedObject[]
     */
    private function getUnion(): array
    {
        return [];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Source\AnotherReturnedObject;
use Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Source\SomeReturnedObject;

final class UnionObjects
{
    public function go()
    {
        $this->run($this->getUnion());
    }

    /**
     * @param \Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Source\AnotherReturnedObject[]|\Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Source\SomeReturnedObject[] $items
     */
    private function run(array $items)
    {
    }

    /**
     * @return SomeReturnedObject[]|AnotherReturnedObject[]
     */
    private function getUnion(): array
    {
        return [];
    }
}

?>
