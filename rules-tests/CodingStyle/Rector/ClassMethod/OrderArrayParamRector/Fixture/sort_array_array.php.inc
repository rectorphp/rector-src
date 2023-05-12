<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Fixture;

use Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Source\Groups;


#[Groups(['b', 'a'], ['d', 'c'])]
class MyClass
{
}

?>
    -----
<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Fixture;

use Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Source\Groups;


#[Groups(['a', 'b'], ['c', 'd'])]
class MyClass
{
}

?>
