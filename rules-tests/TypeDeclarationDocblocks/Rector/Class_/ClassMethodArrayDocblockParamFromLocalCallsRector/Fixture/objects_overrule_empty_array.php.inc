<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Source\SomeReturnedObject;

final class ObjectsOverruleEmptyArray
{
    public function go(array $emptyArray)
    {
        $this->run($this->getItemsWithArray());
        $this->run($this->getItems());

        $this->run($emptyArray);
    }

    public function run(array $result)
    {
    }

    /**
     * @return SomeReturnedObject[]|array
     */
    private function getItems()
    {
        return [];
    }

    /**
     * @return SomeReturnedObject[]
     */
    private function getItemsWithArray()
    {
        return [];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Source\SomeReturnedObject;

final class ObjectsOverruleEmptyArray
{
    public function go(array $emptyArray)
    {
        $this->run($this->getItemsWithArray());
        $this->run($this->getItems());

        $this->run($emptyArray);
    }

    /**
     * @param \Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Source\SomeReturnedObject[] $result
     */
    public function run(array $result)
    {
    }

    /**
     * @return SomeReturnedObject[]|array
     */
    private function getItems()
    {
        return [];
    }

    /**
     * @return SomeReturnedObject[]
     */
    private function getItemsWithArray()
    {
        return [];
    }
}

?>
