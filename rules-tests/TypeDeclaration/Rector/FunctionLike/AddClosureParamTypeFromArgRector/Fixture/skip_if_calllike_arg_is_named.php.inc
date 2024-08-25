<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgRector\Source\ClassForNamedParameters;

SomeClass::someCall(anotherCallback: fn ($var) => $var, callback: fn($var) => $var);

?>
