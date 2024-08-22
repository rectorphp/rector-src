<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclarationRector\Fixture;

use SomeNamespace\SomeClass;
use SomeNamespace\AnotherClass;

SomeClass::someCall(AnotherClass::class, function ($object) {
    return $object;
});

SomeClass::someOtherCall('test', function ($object) {
    return $object;
});

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclarationRector\Fixture;

use SomeNamespace\SomeClass;
use SomeNamespace\AnotherClass;

SomeClass::someCall(AnotherClass::class, function (\SomeNamespace\AnotherClass $object) {
    return $object;
});

SomeClass::someOtherCall('test', function (string $object) {
    return $object;
});

?>
