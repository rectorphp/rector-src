<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictConstantReturnRector\Fixture;

const A = [];

class WithConstant
{
    public function run()
    {
        return A;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictConstantReturnRector\Fixture;

const A = [];

class WithConstant
{
    public function run(): array
    {
        return A;
    }
}

?>
