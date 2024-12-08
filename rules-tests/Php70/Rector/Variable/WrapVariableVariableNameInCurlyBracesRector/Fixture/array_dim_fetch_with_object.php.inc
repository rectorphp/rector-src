<?php

namespace Rector\Tests\Php70\Rector\Variable\WrapVariableVariableNameInCurlyBracesRector\Fixture;

use stdClass;

final class ArrayDimFetchWithObject
{
    public function run()
    {
        $foo = 'a';
        $a = [
            'bar' => new stdClass()
        ];

        echo $$foo['bar'];
    }
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\Variable\WrapVariableVariableNameInCurlyBracesRector\Fixture;

use stdClass;

final class ArrayDimFetchWithObject
{
    public function run()
    {
        $foo = 'a';
        $a = [
            'bar' => new stdClass()
        ];

        echo ${$foo}['bar'];
    }
}

?>
