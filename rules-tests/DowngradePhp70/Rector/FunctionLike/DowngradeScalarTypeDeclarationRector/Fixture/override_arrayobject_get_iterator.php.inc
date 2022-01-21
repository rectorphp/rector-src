<?php

namespace Rector\Tests\DowngradePhp70\Rector\FunctionLike\DowngradeScalarTypeDeclarationRector\Fixture;

/**
 * @see https://github.com/laminas/laminas-stdlib/pull/54
 */
class OverrideArrayObjectGetIterator extends \ArrayObject
{
    public function getIterator(): \Iterator
    {
        $array = $this->getArrayCopy();
        return new ArrayIterator(array_reverse($array));
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp70\Rector\FunctionLike\DowngradeScalarTypeDeclarationRector\Fixture;

/**
 * @see https://github.com/laminas/laminas-stdlib/pull/54
 */
class OverrideArrayObjectGetIterator extends \ArrayObject
{
    /**
     * @return \Iterator
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        $array = $this->getArrayCopy();
        return new ArrayIterator(array_reverse($array));
    }
}

?>
