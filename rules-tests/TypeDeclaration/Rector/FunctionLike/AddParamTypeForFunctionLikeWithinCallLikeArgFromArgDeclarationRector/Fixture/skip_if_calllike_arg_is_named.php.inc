<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclarationRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclarationRector\Source\ClassForNamedParameters;

SomeClass::someCall(anotherCallback: fn ($var) => $var, callback: fn($var) => $var);

?>
