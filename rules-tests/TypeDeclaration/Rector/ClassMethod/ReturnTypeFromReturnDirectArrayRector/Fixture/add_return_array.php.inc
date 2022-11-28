<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnDirectArrayRector\Fixture;

final class AddReturnArray
{
    public function getArray()
    {
        return [1, 2, 3];
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnDirectArrayRector\Fixture;

final class AddReturnArray
{
    public function getArray(): array
    {
        return [1, 2, 3];
    }
}

?>
