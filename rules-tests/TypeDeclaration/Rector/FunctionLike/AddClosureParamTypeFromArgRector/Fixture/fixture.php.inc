<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgRector\Fixture;

use SomeNamespace\SomeClass;
use SomeNamespace\AnotherClass;

SomeClass::someCall('test', function ($object) {
    return $object;
});

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgRector\Fixture;

use SomeNamespace\SomeClass;
use SomeNamespace\AnotherClass;

SomeClass::someCall('test', function (string $object) {
    return $object;
});

?>
