<?php

namespace Rector\Tests\Defluent\Rector\MethodCall\FluentChainMethodCallToNormalMethodCallRector\Fixture;

use Rector\Tests\Defluent\Rector\MethodCall\FluentChainMethodCallToNormalMethodCallRector\Source\MixinClass;

class SkipMixinAsSource
{
    public function someFunction()
    {
        $queryBuilder = new MixinClass();
        $queryBuilder->addQuery()
                        ->select();
    }
}
