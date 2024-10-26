<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Fixture;

/**
 * @template T as mixed
 */
class SkipFromTemplate
{
    /**
     * @param T $param
     */
    public function run($param)
    {
    }
}
