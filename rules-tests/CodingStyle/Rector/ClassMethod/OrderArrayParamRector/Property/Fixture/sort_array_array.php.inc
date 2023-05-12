<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Property\Fixture;

use Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Property\Source\Groups;


class MyClass
{
    #[Groups(['b', 'a'], ['d', 'c'])]
    private ?string $property = null;
}

?>
    -----
<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Property\Fixture;

use Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Property\Source\Groups;


class MyClass
{
    #[Groups(['a', 'b'], ['c', 'd'])]
    private ?string $property = null;
}

?>
