<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Fixture;

final class OnConstructorPromotion
{
    /** @param mixed $foo*/
    public function __construct(public $foo): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Fixture;

final class OnConstructorPromotion
{
    public function __construct(public mixed $foo): void
    {
    }
}

?>
