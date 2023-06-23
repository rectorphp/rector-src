<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ArrayShapeFromConstantArrayReturnRector\Fixture;

final class WithObjectShape
{
    /**
     * @return array<string, stdClass>
     */
    public static function cleanup(?string $value): array
    {
        return [
            // Ensure empty metadata is serialized as `{}` and not `[]`
            'metadata' => (object) array_filter([
                'foo' => $value,
            ]),
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ArrayShapeFromConstantArrayReturnRector\Fixture;

final class WithObjectShape
{
    /**
     * @return array{metadata: \stdClass}
     */
    public static function cleanup(?string $value): array
    {
        return [
            // Ensure empty metadata is serialized as `{}` and not `[]`
            'metadata' => (object) array_filter([
                'foo' => $value,
            ]),
        ];
    }
}

?>
