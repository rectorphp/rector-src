<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use stdClass;

final class AlsoUpdateGenericCollectionDocblock
{
    /**
     * @return Collection<int, stdClass>
     */
    protected function build(): Collection
    {
        return new ArrayCollection([new stdClass()]);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use stdClass;

final class AlsoUpdateGenericCollectionDocblock
{
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection<int, stdClass>
     */
    protected function build(): \Doctrine\Common\Collections\ArrayCollection
    {
        return new ArrayCollection([new stdClass()]);
    }
}

?>
