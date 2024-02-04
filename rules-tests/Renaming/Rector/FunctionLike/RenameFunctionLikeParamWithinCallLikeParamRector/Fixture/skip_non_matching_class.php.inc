<?php

namespace Rector\Tests\Renaming\Rector\FunctionLike\RenameFunctionLikeParamWithinCallLikeParamRector\Fixture;

SomeOtherClass::someCall(function ($qry) {
    return $qry;
});

?>
