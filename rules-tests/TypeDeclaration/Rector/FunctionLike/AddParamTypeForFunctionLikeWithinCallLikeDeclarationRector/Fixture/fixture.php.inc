<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeDeclarationRector\Fixture;

use SomeNamespace\SomeClass;
use SomeNamespace\SomeClassForNamed;

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

SomeClassForNamed::someCall('a', 'b', callback: fn ($var) => $var);

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeDeclarationRector\Fixture;

use SomeNamespace\SomeClass;
use SomeNamespace\SomeClassForNamed;

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

SomeClassForNamed::someCall('a', 'b', callback: fn (string $var) => $var);

?>
