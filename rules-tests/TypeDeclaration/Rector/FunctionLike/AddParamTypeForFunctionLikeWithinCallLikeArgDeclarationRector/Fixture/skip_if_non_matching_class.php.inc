<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeArgDeclarationRector\Fixture;

use SomeNamespace\SomeClass;

AnotherClass::someCall(function ($name) {
    return $name;
});
