<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessLastVariableAssignRector\Fixture;

class EmptyArray
{
    public function run()
    {
        if (\rand(0, 1)) {
            $foo = 1;
            if (\rand(0, 1)) {
                $bar = 2;
                if (\rand(0, 1)) {
                    $baz = 3;
                    if (\rand(0, 1)) {
                        echo '';
                    }
                    return $baz;
                }
                return $bar;
            }
            return $foo;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessLastVariableAssignRector\Fixture;

class EmptyArray
{
    public function run()
    {
        if (\rand(0, 1)) {
            if (\rand(0, 1)) {
                if (\rand(0, 1)) {
                    if (\rand(0, 1)) {
                        echo '';
                    }
                    return 3;
                }
                return 2;
            }
            return 1;
        }
    }
}

?>
