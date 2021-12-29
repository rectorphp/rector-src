<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\FixtureForPhp81;

class DoNotCrashOnNumericStringNoReturnDeclaration
{
    public function run()
    {
        return bcadd('10', '5');
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\FixtureForPhp81;

class DoNotCrashOnNumericStringNoReturnDeclaration
{
    public function run(): string
    {
        return bcadd('10', '5');
    }
}

?>