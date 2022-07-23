<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Fixture;

use Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Source\SomeParentWithNonMixedDoc;

class SkipExtendsParentWithNonMixedDoc extends SomeParentWithNonMixedDoc
{
    public function run($param)
    {
    }
}
