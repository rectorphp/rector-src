<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclarationRector\Fixture;

use SomeNamespace\SomeClass;

SomeClass::someCall('test', fn(int|string $var) => $var);

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclarationRector\Fixture;

use SomeNamespace\SomeClass;

SomeClass::someCall('test', fn(string $var) => $var);

?>
