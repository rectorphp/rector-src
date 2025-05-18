<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector\Fixture;

function () {
    $a = true;
    return $a;
};

function sameVariableInDifferentScope()
{
    $n = array_map(function () {
        return $n + 1;
    }, []);

    return $n;
}

function moreVariableOneWithoutAssignment() {
    $o++;
    $o = 10;

    return $o;
}

function assignmentAsFunctionParameter() {
    doSomething($p = 0);
    return $p;
}

function assignmentAfterAssignment() {
    doSomething($qq = $q = 0);
    return $q;
}

function () {
    $a = 1;
    $b = 1;
    $c = [
        $b-- => $a++,
        --$b => ++$a,
    ];
    return $c;
};

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector\Fixture;

function () {
    return true;
};

function sameVariableInDifferentScope()
{
    return array_map(function () {
        return $n + 1;
    }, []);
}

function moreVariableOneWithoutAssignment() {
    $o++;

    return 10;
}

function assignmentAsFunctionParameter() {
    doSomething($p = 0);
    return $p;
}

function assignmentAfterAssignment() {
    doSomething($qq = $q = 0);
    return $q;
}

function () {
    $a = 1;
    $b = 1;
    return [
        $b-- => $a++,
        --$b => ++$a,
    ];
};

?>
