<?php

namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Fixture;

class Foo
{

}

final class SkipIfClassExtendsFromAClassThatIsNotReadonly extends Foo
{
    public function __construct()
    {
    }
}
