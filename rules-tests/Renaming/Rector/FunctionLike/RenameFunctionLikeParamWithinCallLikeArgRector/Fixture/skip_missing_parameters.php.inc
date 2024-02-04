<?php

namespace Rector\Tests\Renaming\Rector\FunctionLike\RenameFunctionLikeParamWithinCallLikeArgRector\Fixture;

use SomeNamespace\SomeClass;

SomeClass::someOtherCall('a', function () {
});

?>
