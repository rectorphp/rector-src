<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnCastRector\Fixture;

final class AddReturnObjectByCast
{
    public function getObject($data)
    {
        try {
            return (object) $data;
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

final class AddReturnObjectByCast
{
    public function getObject($data): \stdClass
    {
        try {
            return (object) $data;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

?>
