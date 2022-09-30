<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessLastVariableAssignRector\Fixture;

class VarVar
{
    public function run()
    {
        $other   = 'content';
        $$other  = 'foo';
        $content = 1;
        return $content;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessLastVariableAssignRector\Fixture;

class VarVar
{
    public function run()
    {
        $other   = 'content';
        $$other  = 'foo';
        return 1;
    }
}

?>
