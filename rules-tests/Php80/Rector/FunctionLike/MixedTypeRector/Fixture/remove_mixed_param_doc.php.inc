<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Fixture;

class RemoveMixedParamDoc
{
    /**
     * @param mixed $param
     */
    public function run(mixed $param)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Fixture;

class RemoveMixedParamDoc
{
    public function run(mixed $param)
    {
    }
}

?>
