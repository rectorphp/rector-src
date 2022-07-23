<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Source;

class SomeParentWithNonMixedDoc
{
    /**
     * @param int $param
     */
    public function run($param)
    {
    }
}