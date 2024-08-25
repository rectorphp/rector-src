<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgClassStringRector\Fixture;

use SomeNamespace\SomeClass;

SomeClass::someCall(SomeClass::class, fn(object $var) => $var);

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgClassStringRector\Fixture;

use SomeNamespace\SomeClass;

SomeClass::someCall(SomeClass::class, fn(\SomeNamespace\SomeClass $var) => $var);

?>
