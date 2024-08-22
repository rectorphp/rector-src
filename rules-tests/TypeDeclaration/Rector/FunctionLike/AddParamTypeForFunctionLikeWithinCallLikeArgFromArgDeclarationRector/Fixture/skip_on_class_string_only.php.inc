<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclarationRector\Fixture;

use SomeNamespace\SomeClass;
use SomeNamespace\AnotherClass;

SomeClass::someCall('test', function ($object) {
    return $object;
});

?>
