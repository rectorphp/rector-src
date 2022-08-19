<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\FixtureUnionIntersectionTypes;

use MockObject;
use ObjectRepository;

final class DemoFile
{
    /**
     * @var MockObject&ObjectRepository<ItemCountry>
     */
    private $itemCountryRepo;
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\FixtureUnionIntersectionTypes;

use MockObject;
use ObjectRepository;

final class DemoFile
{
    /**
     * @var MockObject&ObjectRepository<ItemCountry>
     */
    private \MockObject&\ObjectRepository $itemCountryRepo;
}

?>
