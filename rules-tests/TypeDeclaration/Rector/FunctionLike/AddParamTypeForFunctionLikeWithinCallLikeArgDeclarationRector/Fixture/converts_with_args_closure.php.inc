<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeArgDeclarationRector\Fixture;

use SomeNamespace\SomeClass;
use SomeNamespace\SomeClassForNamed;
use SomeNamespace\AnotherClass;

SomeClass::someDynamicCall(AnotherClass::class, function ($object) {
    return $object;
});

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeArgDeclarationRector\Fixture;

use SomeNamespace\SomeClass;
use SomeNamespace\SomeClassForNamed;
use SomeNamespace\AnotherClass;

SomeClass::someDynamicCall(AnotherClass::class, function (\SomeNamespace\AnotherClass $object) {
    return $object;
});

?>
