<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorReadonlyClassRector\Fixture;

use Doctrine\Common\Collections\ArrayCollection;

final class SkipDoctrineCollection
{
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }
}
