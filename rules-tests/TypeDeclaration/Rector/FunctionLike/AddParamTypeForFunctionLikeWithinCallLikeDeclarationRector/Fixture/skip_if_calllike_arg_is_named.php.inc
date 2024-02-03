<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeDeclarationRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeDeclarationRector\Source\ClassForNamedParameters;

ClassForNamedParameters::someCall(anotherCallback: fn ($var) => $var, callback: fn($var) => $var);

?>
