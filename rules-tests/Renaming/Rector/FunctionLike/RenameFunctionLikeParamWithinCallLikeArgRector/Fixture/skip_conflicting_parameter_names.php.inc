<?php

namespace Rector\Tests\Renaming\Rector\FunctionLike\RenameFunctionLikeParamWithinCallLikeArgRector\Fixture;

use SomeNamespace\SomeClass;
use SomeNamespace\SomeClassForNamed;

function skip_conflicting_parameter_names()
{
    SomeClass::someCall(function ($qry, $query) {
        return $qry;
    });

    SomeClass::someCall(fn ($qry, $query) => $qry);

    SomeClass::someCall(function ($qry, $query) {
        echo $query;
        return $qry;
    });

    $query = 'a';

    SomeClass::someCall(function ($qry) use ($query) {
        return $query;
    });

    SomeClass::someCall(function ($qry) use ($query) {
        return $qry;
    });

    SomeClass::someCall(function ($qryFive) {
        echo $qryFive;
        return function ($query) {
            return $query;
        };
    });
}

?>
