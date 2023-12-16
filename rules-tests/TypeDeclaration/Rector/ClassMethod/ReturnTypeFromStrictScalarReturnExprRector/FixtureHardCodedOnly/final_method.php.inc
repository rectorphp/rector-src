<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\FixtureHardCodedOnly;

class UntypedBaseClass
{
    public function test()
    {
        return 0;
    }
}

class SomeChild2 extends UntypedBaseClass
{
    final public function test()
    {
        return 'abc';
    }

}

class SomeChild2Child extends SomeChild2 {}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\FixtureHardCodedOnly;

class UntypedBaseClass
{
    public function test()
    {
        return 0;
    }
}

class SomeChild2 extends UntypedBaseClass
{
    final public function test(): string
    {
        return 'abc';
    }

}

class SomeChild2Child extends SomeChild2 {}

?>
