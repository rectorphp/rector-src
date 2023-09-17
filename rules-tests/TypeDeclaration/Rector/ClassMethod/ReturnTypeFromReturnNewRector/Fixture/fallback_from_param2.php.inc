<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

final class FallbackFromParam2
{
    public function action(self $obj)
    {
        if (rand(0, 1)) {
            return new FallbackFromParam2();
        }

        return $obj;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

final class FallbackFromParam2
{
    public function action(self $obj): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture\FallbackFromParam2
    {
        if (rand(0, 1)) {
            return new FallbackFromParam2();
        }

        return $obj;
    }
}

?>
