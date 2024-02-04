<?php

namespace Rector\Tests\Renaming\Rector\FunctionLike\RenameFunctionLikeParamWithinCallLikeParamRector\Fixture;

use SomeNamespace\SomeClass;
use SomeNamespace\SomeClassForNamed;

SomeClass::someCall(function ($qry) {
    return $qry;
});

$var = new SomeClass();
$var->someCall(function ($qry) {
    return $qry;
});

(new SomeClass())->someCall(function ($qry) {
    return $qry;
});

$var->someCall(fn ($qry) => $qry);

SomeClassForNamed::someCall('a', 'b', callback: fn ($var) => $var);

SomeClass::someCall(function ($qry) {
    echo $qry;
    return function ($query) {
        return $query;
    };
});

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\FunctionLike\RenameFunctionLikeParamWithinCallLikeParamRector\Fixture;

use SomeNamespace\SomeClass;
use SomeNamespace\SomeClassForNamed;

SomeClass::someCall(function ($query) {
    return $query;
});

$var = new SomeClass();
$var->someCall(function ($query) {
    return $query;
});

(new SomeClass())->someCall(function ($query) {
    return $query;
});

$var->someCall(fn ($query) => $query);

SomeClassForNamed::someCall('a', 'b', callback: fn ($query) => $query);

SomeClass::someCall(function ($query) {
    echo $query;
    return function ($query) {
        return $query;
    };
});

?>
