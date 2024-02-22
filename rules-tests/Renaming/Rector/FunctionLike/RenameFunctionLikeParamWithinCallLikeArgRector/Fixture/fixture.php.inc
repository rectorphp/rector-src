<?php

namespace Rector\Tests\Renaming\Rector\FunctionLike\RenameFunctionLikeParamWithinCallLikeArgRector\Fixture;

use SomeNamespace\SomeClass;
use SomeNamespace\SomeClassForNamed;

function fixture()
{
    SomeClass::someCall(function ($qryOne) {
        return $qryOne;
    });

    $varOne = new SomeClass();
    $varOne->someCall(function ($qryTwo) {
        return $qryTwo;
    });

    (new SomeClass())->someCall(function ($qryThree) {
        return $qryThree;
    });

    $varOne->someCall(fn($qryFour) => $qryFour);

    SomeClassForNamed::someCall('a', 'b', callback: fn($varTwo) => $varTwo);
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\FunctionLike\RenameFunctionLikeParamWithinCallLikeArgRector\Fixture;

use SomeNamespace\SomeClass;
use SomeNamespace\SomeClassForNamed;

function fixture()
{
    SomeClass::someCall(function ($query) {
        return $query;
    });

    $varOne = new SomeClass();
    $varOne->someCall(function ($query) {
        return $query;
    });

    (new SomeClass())->someCall(function ($query) {
        return $query;
    });

    $varOne->someCall(fn($query) => $query);

    SomeClassForNamed::someCall('a', 'b', callback: fn($query) => $query);
}

?>
