<?php

namespace Rector\Tests\TypeDeclaration\Rector\Expression\InlineVarDocTagToAssertRector\Fixture;

function test() {
    /** @var string $foo */
    $foo = getString();
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Expression\InlineVarDocTagToAssertRector\Fixture;

function test() {
    $foo = getString();
    assert(is_string($foo));
}

?>
