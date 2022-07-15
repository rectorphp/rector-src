<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeDeclarationFromStrictConstantReturnRector\Fixture;

class SomeClass
{
    public const NAME = 'name';

    public function run()
    {
        return self::NAME;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeDeclarationFromStrictConstantReturnRector\Fixture;

class SomeClass
{
    public const NAME = 'name';

    public function run(): string
    {
        return self::NAME;
    }
}

?>
