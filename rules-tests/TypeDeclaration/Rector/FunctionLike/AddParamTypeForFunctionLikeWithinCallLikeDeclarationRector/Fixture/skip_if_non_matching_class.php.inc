<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeDeclarationRector\Fixture;

use SomeNamespace\SomeClass;

AnotherClass::someCall(function ($name) {
    return $name;
});
