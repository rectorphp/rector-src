<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessLastVariableAssignRector\Fixture;

class EmptyArray
{
    public function run()
    {
        $content = [];
        if (\rand(0, 1)) {
            echo '';
        }

        return $content;
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
            echo '';
        }

        return [];
    }
}

?>
