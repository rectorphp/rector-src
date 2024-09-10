<?php

namespace Rector\Tests\TypeDeclaration\Rector\Expression\InlineVarDocTagToAssertRector\Fixture;

class OtherClass {}

class SomeClass extends OtherClass
{
    function test()
    {
        /** @var self $foo */
        $foo = getSelf();

        /** @var static $foo */
        $foo = getStatic();

        /** @var parent $foo */
        $foo = getParent();
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Expression\InlineVarDocTagToAssertRector\Fixture;

class OtherClass {}

class SomeClass extends OtherClass
{
    function test()
    {
        $foo = getSelf();
        assert($foo instanceof self);

        $foo = getStatic();
        assert($foo instanceof static);

        $foo = getParent();
        assert($foo instanceof parent);
    }
}

?>
