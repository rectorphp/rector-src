<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Fixture;

/**
 * @template T as mixed
 */
class FromTemplate
{
    /**
     * @param T $param
     */
    public function run($param)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Fixture;

/**
 * @template T as mixed
 */
class FromTemplate
{
    /**
     * @param T $param
     */
    public function run(mixed $param)
    {
    }
}

?>