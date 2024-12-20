<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector\Fixture;

class Foobar {
    function & test()
    {
        $var = 9000;
        return $var;
    }
}


function & () {
    $var = 26;
    return $var;
};

function & notAnonymous()
{
    for ($i = 0; $i < 4; $i++) {
        $var = 42;
        return $var;
    }
}

function & notAnonymous2()
{
    for ($i = 0; $i < 4; $i++) {
        switch ($i) {
            case 1:
                $var = 42;
                return $var;
        }
    }
}
