<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromObjectRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromObjectRector\Source\SimpleContainer;
use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromObjectRector\Source\SomeType;

SimpleContainer::someCall(SomeType::class, fn(object $var) => $var);

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromObjectRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromObjectRector\Source\SimpleContainer;
use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromObjectRector\Source\SomeType;

SimpleContainer::someCall(SomeType::class, fn(\Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromObjectRector\Source\SimpleContainer $var) => $var);

?>
