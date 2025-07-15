<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Fixture;

class ReplaceMethodArgumentWithConstant
{
    public function setSomeMethod($value)
    {
        // ...
    }
}

$object = new ReplaceMethodArgumentWithConstant();
$object->setSomeMethod('some value');
$object->setSomeMethod('some extra value');

?>

-----
<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Fixture;

class ReplaceMethodArgumentWithConstant
{
    public function setSomeMethod($value)
    {
        // ...
    }
}

$object = new ReplaceMethodArgumentWithConstant();
$object->setSomeMethod(\SomeClass::SOME_CONSTANT);
$object->setSomeMethod(\SomeClass::SOME_EXTRA_CONSTANT);

?>
