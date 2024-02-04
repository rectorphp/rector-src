<?php

namespace Rector\Tests\Renaming\Rector\FunctionLike\RenameFunctionLikeParamWithinCallLikeArgRector\Fixture;

SomeOtherClass::someCall(function ($qry) {
    return $qry;
});

?>
