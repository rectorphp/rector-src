<?php

namespace Rector\Tests\Transform\Rector\ClassMethod\ReturnTypeWillChangeRector\Fixture;

/**
 * @see https://github.com/laminas/laminas-stdlib/pull/54
 */
class OverrideArrayObjectGetIterator extends \ArrayObject
{
    public function getIterator()
    {
        $array = $this->getArrayCopy();
        return new ArrayIterator(array_reverse($array));
    }
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\ClassMethod\ReturnTypeWillChangeRector\Fixture;

/**
 * @see https://github.com/laminas/laminas-stdlib/pull/54
 */
class OverrideArrayObjectGetIterator extends \ArrayObject
{
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        $array = $this->getArrayCopy();
        return new ArrayIterator(array_reverse($array));
    }
}

?>
