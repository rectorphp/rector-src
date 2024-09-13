<?php

namespace Rector\Tests\TypeDeclaration\Rector\Expression\InlineVarDocTagToAssertRector\Fixture;

final class SomeClass
{
    function test() {
        /** @var string $foo */
        $foo = getString();
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Expression\InlineVarDocTagToAssertRector\Fixture;

final class SomeClass
{
    function test() {
        $foo = getString();
        assert(is_string($foo));
    }
}

?>
