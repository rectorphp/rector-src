<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Fixture;

class NoMixedParamDoc
{
    public function run($param)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Fixture;

class NoMixedParamDoc
{
    public function run(mixed $param)
    {
    }
}

?>