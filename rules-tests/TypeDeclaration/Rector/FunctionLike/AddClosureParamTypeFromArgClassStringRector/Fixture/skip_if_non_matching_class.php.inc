<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgClassStringRector\Fixture;

use SomeNamespace\SomeClass;

AnotherClass::someCall(function ($name) {
    return $name;
});
