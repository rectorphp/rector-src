<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnDirectArrayRector\Fixture;

/**
 * Both classes have to be in the same fixture, as the one that needs to be skipped is the parent class
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
 * Both classes have to be in the same fixture, as the one that needs to be skipped is the parent class
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