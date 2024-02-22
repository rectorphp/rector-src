<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeArgDeclarationRector\Fixture;

use SomeNamespace\SomeClass;

SomeClass::someCall(fn(int|string $var) => $var);

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeArgDeclarationRector\Fixture;

use SomeNamespace\SomeClass;

SomeClass::someCall(fn(string $var) => $var);

?>
