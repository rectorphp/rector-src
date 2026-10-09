<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector\Fixture;

trait ReturnNewParentInTrait
{
    public function createParentClosure()
    {
        return static function () {
            return new parent();
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector\Fixture;

trait ReturnNewParentInTrait
{
    public function createParentClosure()
    {
        return static function (): parent {
            return new parent();
        };
    }
}

?>
