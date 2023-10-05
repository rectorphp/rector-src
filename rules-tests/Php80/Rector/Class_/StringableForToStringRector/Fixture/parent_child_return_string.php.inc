<?php

namespace Rector\Tests\Php80\Rector\Class_\StringableForToStringRector\Fixture;

class ParentChildReturnString
{
    public function __toString()
    {
        return 'I can stringz';
    }
}

class SomeChildReturnParentChildReturnString extends ParentChildReturnString
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

class ParentChildReturnString implements \Stringable
{
    public function __toString(): string
    {
        return 'I can stringz';
    }
}

class SomeChildReturnParentChildReturnString extends ParentChildReturnString
{
    public function __toString(): string
    {
        return 'I can stringz';
    }
}

?>
