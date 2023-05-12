<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Class\Fixture;

use Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Class\Source\Groups;


#[Groups(['b', 'a'], ['d', 'c'])]
class MyClass
{
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Class\Fixture;

use Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Class\Source\Groups;


#[Groups(['a', 'b'], ['c', 'd'])]
class MyClass
{
}

?>
