<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

class UpperVoid
{
    public function upperVoid()
    {
        new class {
            public function toString()
            {
                return 'test';
            }
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

class UpperVoid
{
    public function upperVoid(): void
    {
        new class {
            public function toString(): string
            {
                return 'test';
            }
        };
    }
}

?>
