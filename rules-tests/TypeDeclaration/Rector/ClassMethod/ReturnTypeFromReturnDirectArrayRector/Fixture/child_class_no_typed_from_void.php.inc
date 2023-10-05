<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnDirectArrayRector\Fixture;

/**
 * Both class has to be in same fixture as the one that need to be skipped is the parent class
 */
class SkipChildNoTypedFromVoid
{
    public function get()
    {
    }
}

class SomeChildFromNoVoid extends SkipChildNoTypedFromVoid
{
    public function get()
    {
        return [];
    }

}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnDirectArrayRector\Fixture;

/**
 * Both class has to be in same fixture as the one that need to be skipped is the parent class
 */
class SkipChildNoTypedFromVoid
{
    public function get()
    {
    }
}

class SomeChildFromNoVoid extends SkipChildNoTypedFromVoid
{
    public function get(): array
    {
        return [];
    }

}

?>