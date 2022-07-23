<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Fixture;

use Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Source\SomeParentWithNonMixedTyped;

class SkipExtendsParentWithNonMixedTyped extends SomeParentWithNonMixedTyped
{
    public function run($param)
    {
    }
}
