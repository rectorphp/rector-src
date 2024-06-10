<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Fixture;

class OnPrivateMethod
{
    /**
     * @param mixed $param
     */
    private function run($param)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Fixture;

class OnPrivateMethod
{
    private function run(mixed $param)
    {
    }
}

?>
