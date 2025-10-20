<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

$fn = function ($foo)
{
    return $foo($foo);
};

var_dump($fn('strlen'));

?>
