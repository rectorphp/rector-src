<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Property\Fixture;

use Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Property\Source\Groups;


class MyClass
{
    #[Groups('a', ['b', 'a'])]
    private ?string $property = null;
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Property\Fixture;

use Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Property\Source\Groups;


class MyClass
{
    #[Groups('a', ['a', 'b'])]
    private ?string $property = null;
}

?>
