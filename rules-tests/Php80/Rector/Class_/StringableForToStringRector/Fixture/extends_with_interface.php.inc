<?php

namespace Rector\Tests\Php80\Rector\Class_\StringableForToStringRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\StringableForToStringRector\Source\ParentInterfaceWithStringable;

final class ExtendsWithInterface implements ParentInterfaceWithStringable
{
    public function __toString()
    {
        return 'I can stringz';
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\StringableForToStringRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\StringableForToStringRector\Source\ParentInterfaceWithStringable;

final class ExtendsWithInterface implements ParentInterfaceWithStringable
{
    public function __toString(): string
    {
        return 'I can stringz';
    }
}

?>
