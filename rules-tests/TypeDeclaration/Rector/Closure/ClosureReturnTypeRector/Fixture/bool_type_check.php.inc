<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector\Fixture;

final class BoolTypeCheck
{
    public function run($items)
    {
        return array_filter($items, function ($item) {
            return is_string($item);
        });
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector\Fixture;

final class BoolTypeCheck
{
    public function run($items)
    {
        return array_filter($items, function ($item): bool {
            return is_string($item);
        });
    }
}

?>
