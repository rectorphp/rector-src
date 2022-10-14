<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\FixtureUnionIntersectionTypes;

final class SkipObjectTypeNotExists
{
    /**
     * @var NotExistClass1&NotExistClass2<ItemCountry>
     */
    private $itemCountryRepo;
}
