<?php

namespace Rector\Tests\Renaming\Rector\FunctionLike\RenameFunctionLikeParamWithinCallLikeParamRector\Fixture;

use SomeNamespace\SomeClass;

SomeClass::someOtherCall('a', function ($qry) {
    return $qry;
});

?>
