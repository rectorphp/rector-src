<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Fixture;

use Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Source\Groups;


#[Groups('a', ['b', 'a'])]
class MyClass
{
}

?>
    -----
<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Fixture;

use Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Source\Groups;


#[Groups('a', ['a', 'b'])]
class MyClass
{
}

?>
