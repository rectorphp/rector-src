<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Fixture;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class SkipUnionIntersection
{
    public function run()
    {
        $items = ['a', 'b', 'b', 'c'];

        $reduces = \array_reduce($items, static function (Collection $carry, string $item) {
            if (!$carry->contains($item)) {
                $carry->add($item);
            }

            return $carry;
        }, new ArrayCollection());
    }
}
