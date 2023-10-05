<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnDirectArrayRector\Fixture;

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
