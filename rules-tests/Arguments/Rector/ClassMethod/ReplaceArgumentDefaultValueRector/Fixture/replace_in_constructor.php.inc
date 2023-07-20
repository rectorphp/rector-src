<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Fixture;

final class ReplaceInConstructor
{
    public function __construct(string $someValue)
    {
    }
}


$result = new ReplaceInConstructor('some value');

?>
-----
<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Fixture;

final class ReplaceInConstructor
{
    public function __construct(string $someValue)
    {
    }
}


$result = new ReplaceInConstructor(\SomeClass::SOME_CONSTANT);

?>
