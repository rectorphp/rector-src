<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanStrictReturnsRector\Fixture;

class FuncCallFound
{
    public function run()
    {
        return in_array('a', ['a', 'b'], true);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanStrictReturnsRector\Fixture;

class FuncCallFound
{
    public function run(): bool
    {
        return in_array('a', ['a', 'b'], true);
    }
}

?>