<?php

namespace Rector\Tests\Php80\Rector\Class_\StringableForToStringRector\Fixture;

interface InterfaceClass extends \Stringable {
}

class SomeClass implements InterfaceClass
{
    public function __toString()
    {
        return 'I can stringz';
    }
}

?>
