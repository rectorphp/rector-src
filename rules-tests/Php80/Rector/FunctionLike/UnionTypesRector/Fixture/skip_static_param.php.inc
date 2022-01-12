<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\UnionTypesRector\Fixture;

class SkipStaticParam
{
    /**
     * @param static $arg1
     * @param self $arg2
     * @return static
     */
    public function go(self $arg1, self $arg2): static
    {
        return $this;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\UnionTypesRector\Fixture;

class SkipStaticParam
{
    /**
     * @param static $arg1
     */
    public function go(self $arg1, self $arg2): static
    {
        return $this;
    }
}

?>
