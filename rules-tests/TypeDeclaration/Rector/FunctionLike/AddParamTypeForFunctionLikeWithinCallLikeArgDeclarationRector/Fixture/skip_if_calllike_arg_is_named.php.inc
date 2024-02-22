<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeArgDeclarationRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeArgDeclarationRector\Source\ClassForNamedParameters;

SomeClass::someCall(anotherCallback: fn ($var) => $var, callback: fn($var) => $var);

?>
