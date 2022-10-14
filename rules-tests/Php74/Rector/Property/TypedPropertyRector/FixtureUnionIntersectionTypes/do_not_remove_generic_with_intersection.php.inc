<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\FixtureUnionIntersectionTypes;

use PHPUnit\Framework\MockObject\MockObject;
use Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Source\Doctrine\ObjectRepository;

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

use PHPUnit\Framework\MockObject\MockObject;
use Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Source\Doctrine\ObjectRepository;

final class DemoFile
{
    /**
     * @var MockObject&ObjectRepository<ItemCountry>
     */
    private \PHPUnit\Framework\MockObject\MockObject&\Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Source\Doctrine\ObjectRepository $itemCountryRepo;
}

?>
