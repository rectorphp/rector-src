<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

class SkipVoidAndOther
{
    public function getSome($value)
    {
        if ($value !== 5) {
            return;
        }

        return $value !== 10;
    }
}
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

class SkipVoidAndOther
{
    /**
     * @return bool|void
     */
    public function getSome($value)
    {
        if ($value !== 5) {
            return;
        }

        return $value !== 10;
    }
}
