<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\ClassMethod\Fixture;

use Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\ClassMethod\Source\Groups;


class MyClass
{
    private ?string $property = null;

    #[Groups('a', ['b', 'a'])]
    public function getProperty(): ?string
    {
        return $this->property;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\ClassMethod\Fixture;

use Rector\Tests\CodingStyle\Rector\ClassMethod\OrderArrayParamRector\ClassMethod\Source\Groups;


class MyClass
{
    private ?string $property = null;

    #[Groups('a', ['a', 'b'])]
    public function getProperty(): ?string
    {
        return $this->property;
    }
}

?>
