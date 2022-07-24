<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Fixture;

use Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Source\SomeParentNotMixedDocParam;

class SkipOverrideParentMethod extends SomeParentNotMixedDocParam
{
    /**
     * @param mixed $param
     */
    public function run($param)
    {
    }
}