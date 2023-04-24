<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\UnionTypesRector\Fixture;

class SkipNarrowBoolFalseAnotherType
{
    /**
     * @param bool|int|false $param
     * @return bool|int|false
     */
    public function go($param)
    {
        if (rand(0, 1)) {
            return rand(0, 1) ? true : false;
        }

        return 1;
    }
}
