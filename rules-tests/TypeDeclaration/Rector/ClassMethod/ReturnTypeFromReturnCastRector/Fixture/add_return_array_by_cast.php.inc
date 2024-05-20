<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnCastRector\Fixture;

final class AddReturnArrayByCast
{
    public function getArray($data)
    {
        try {
            return (array) $data;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnCastRector\Fixture;

final class AddReturnArrayByCast
{
    public function getArray($data): array
    {
        try {
            return (array) $data;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

?>
