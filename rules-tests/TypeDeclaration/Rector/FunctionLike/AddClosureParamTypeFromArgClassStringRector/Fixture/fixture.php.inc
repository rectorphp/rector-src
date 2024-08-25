<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgClassStringRector\Fixture;

use SomeNamespace\SomeClass;
use SomeNamespace\AnotherClass;

SomeClass::someCall(SomeClass::class, function ($object) {
    return $object;
});

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgClassStringRector\Fixture;

use SomeNamespace\SomeClass;
use SomeNamespace\AnotherClass;

SomeClass::someCall(SomeClass::class, function (\SomeNamespace\SomeClass $object) {
    return $object;
});

?>
