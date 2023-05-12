<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Class\Fixture;

use Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Class\Source\Groups;


#[Groups('a', ['b', 'a'])]
class MyClass
{
}

?>
    -----
<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Class\Fixture;

use Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Class\Source\Groups;


#[Groups('a', ['a', 'b'])]
class MyClass
{
}

?>
