<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\UnionTypesRector\Fixture;

class NarrowBoolFalse
{
    /**
     * @param bool|false $param
     * @return bool|false
     */
    public function go($param)
    {
        return rand(0, 1) ? true : false;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\UnionTypesRector\Fixture;

class NarrowBoolFalse
{
    public function go(bool $param): bool
    {
        return rand(0, 1) ? true : false;
    }
}

?>
