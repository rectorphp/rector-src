<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Fixture;

class Fixture
{
    /**
     * @param mixed $param
     */
    public function run($param)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Fixture;

class Fixture
{
    public function run(mixed $param)
    {
    }
}

?>