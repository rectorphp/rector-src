<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

final class FallbackFromParamSelf
{
    public function action(self $obj)
    {
        if (rand(0, 1)) {
            return new FallbackFromParamSelf();
        }

        return $obj;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

final class FallbackFromParamSelf
{
    public function action(self $obj): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture\FallbackFromParamSelf
    {
        if (rand(0, 1)) {
            return new FallbackFromParamSelf();
        }

        return $obj;
    }
}

?>
