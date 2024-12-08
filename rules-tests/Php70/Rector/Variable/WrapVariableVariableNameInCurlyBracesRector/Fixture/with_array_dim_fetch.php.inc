<?php

namespace Rector\Tests\Php70\Rector\Variable\WrapVariableVariableNameInCurlyBracesRector\Fixture;

final class WithArrayDimFetch
{
    public function run()
    {
        $baz = 'bat';
        $foo = array('bar' => 'baz');

        echo $$foo['bar'];
    }
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\Variable\WrapVariableVariableNameInCurlyBracesRector\Fixture;

final class WithArrayDimFetch
{
    public function run()
    {
        $baz = 'bat';
        $foo = array('bar' => 'baz');

        echo ${$foo['bar']};
    }
}

?>
