<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\FuncGetArgsToVariadicParamRector\Fixture;

class InnerClosure {
    public function test() {
        $funcArgs = [];
        $a = function () use (&$funcArgs): void {
            $funcArgs[] = func_get_args();
        };
        $a(1, 2, 3);

        return $funcArgs;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\FuncGetArgsToVariadicParamRector\Fixture;

class InnerClosure {
    public function test() {
        $funcArgs = [];
        $a = function (...$args) use (&$funcArgs): void {
            $funcArgs[] = $args;
        };
        $a(1, 2, 3);

        return $funcArgs;
    }
}

?>
