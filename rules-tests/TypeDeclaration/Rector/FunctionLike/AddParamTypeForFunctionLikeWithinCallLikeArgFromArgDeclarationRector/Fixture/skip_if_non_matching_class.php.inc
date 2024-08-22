<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclarationRector\Fixture;

use SomeNamespace\SomeClass;

AnotherClass::someCall(function ($name) {
    return $name;
});
