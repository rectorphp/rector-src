<?php

namespace Rector\Tests\DowngradePhp70\Rector\FunctionLike\DowngradeScalarTypeDeclarationRector\Fixture;

class ArrayStack extends \ArrayObject
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

class ArrayStack extends \ArrayObject
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
