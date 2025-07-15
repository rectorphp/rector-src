<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Fixture;

final class ReplaceMultipleConstructors
{
    public function __construct(string $someValue)
    {
    }
}


$resultOne = new ReplaceInConstructor('some value');
$resultTwo = new ReplaceInConstructor('some extra value');

?>
-----
<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Fixture;

final class ReplaceMultipleConstructors
{
    public function __construct(string $someValue)
    {
    }
}


$resultOne = new ReplaceInConstructor(\SomeClass::SOME_CONSTANT);
$resultTwo = new ReplaceInConstructor(\SomeClass::SOME_EXTRA_CONSTANT);

?>
