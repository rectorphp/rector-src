<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class ArrayShapeSimpleArray
{
    public function run()
    {
        $items = [];

        $items['one'] = 1;
        $items['two'] = 1;

        return $items;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class ArrayShapeSimpleArray
{
    public function run(): array
    {
        $items = [];

        $items['one'] = 1;
        $items['two'] = 1;

        return $items;
    }
}

?>
