<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeDeclarationRector\Fixture;

use SomeNamespace\SomeClass;

SomeClass::someCall(function ($name) {
    return $name;
});

$var = new SomeClass();
$var->someCall(function ($name) {
    return $name;
});

(new SomeClass())->someCall(function ($name) {
    return $name;
});

$var->someCall(fn ($name) => $name);

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeDeclarationRector\Fixture;

use SomeNamespace\SomeClass;

SomeClass::someCall(function (string $name) {
    return $name;
});

$var = new SomeClass();
$var->someCall(function (string $name) {
    return $name;
});

(new SomeClass())->someCall(function (string $name) {
    return $name;
});

$var->someCall(fn (string $name) => $name);

?>
