<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnDirectArrayRector\Fixture;

/**
 * Both class has to be in same fixture as the one that need to be skipped is the parent class
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
 * Both class has to be in same fixture as the one that need to be skipped is the parent class
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
