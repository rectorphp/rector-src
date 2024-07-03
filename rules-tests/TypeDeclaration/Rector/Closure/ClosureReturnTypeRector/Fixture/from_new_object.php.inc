<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector\Fixture;

final class FromNewObject
{
    public function run($items)
    {
        $dateTime = function ($item) {
            return new \DateTime();
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector\Fixture;

final class FromNewObject
{
    public function run($items)
    {
        $dateTime = function ($item): \DateTime {
            return new \DateTime();
        };
    }
}

?>
