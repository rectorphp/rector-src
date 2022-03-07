<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ArrayShapeFromConstantArrayReturnRector\Fixture;

final class GoOneLevel
{
    public function run(string $name)
    {
        return [
            'first' => [
                'second' => [
                    'third' => $name
                ]
            ]
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ArrayShapeFromConstantArrayReturnRector\Fixture;

final class GoOneLevel
{
    /**
     * @return array{first: array{second: mixed[]}}
     */
    public function run(string $name)
    {
        return [
            'first' => [
                'second' => [
                    'third' => $name
                ]
            ]
        ];
    }
}

?>
