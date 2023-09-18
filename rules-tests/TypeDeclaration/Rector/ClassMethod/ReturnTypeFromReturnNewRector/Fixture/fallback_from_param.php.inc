<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Source\SomeResponse;

final class FallbackFromParam
{
    public function action(SomeResponse $someResponse)
    {
        if (rand(0, 1)) {
            return new SomeResponse();
        }

        return $someResponse;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Source\SomeResponse;

final class FallbackFromParam
{
    public function action(SomeResponse $someResponse): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Source\SomeResponse
    {
        if (rand(0, 1)) {
            return new SomeResponse();
        }

        return $someResponse;
    }
}

?>
