<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnDirectArrayRector\Fixture;

/**
 * Both classes have to be in the same fixture, as the one that needs to be skipped is the parent class
 */
class ParentAndChildReturnArray
{
    public function get()
    {
        return [];
    }
}

class ChildReturnArray extends ParentAndChildReturnArray
{
    public function get()
    {
        return [1];
    }

}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnDirectArrayRector\Fixture;

/**
 * Both classes have to be in the same fixture, as the one that needs to be skipped is the parent class
 */
class ParentAndChildReturnArray
{
    public function get()
    {
        return [];
    }
}

class ChildReturnArray extends ParentAndChildReturnArray
{
    public function get(): array
    {
        return [1];
    }

}

?>
