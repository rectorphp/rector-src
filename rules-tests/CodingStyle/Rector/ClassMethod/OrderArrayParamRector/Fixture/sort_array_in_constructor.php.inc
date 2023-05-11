<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\OrderAttributesRector\SpecificOrder\Fixture;

use Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Source\Groups;


#[Groups(['b', 'a'])]
class MyClass
{
}

?>
    -----
<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\OrderAttributesRector\SpecificOrder\Fixture;

use Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\Source\Groups;


#[Groups(['a', 'b'])]
class MyClass
{
}

?>
