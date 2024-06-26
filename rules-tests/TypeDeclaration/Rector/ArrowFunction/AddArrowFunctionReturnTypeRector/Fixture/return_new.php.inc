<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector\Fixture;

final class ReturnNew
{
    public function action()
    {
        return fn() => new \stdClass();
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector\Fixture;

final class ReturnNew
{
    public function action()
    {
        return fn(): \stdClass => new \stdClass();
    }
}

?>
