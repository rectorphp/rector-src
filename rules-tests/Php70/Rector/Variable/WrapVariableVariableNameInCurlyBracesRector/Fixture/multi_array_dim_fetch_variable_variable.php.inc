<?php

namespace Rector\Tests\Php70\Rector\Variable\WrapVariableVariableNameInCurlyBracesRector\Fixture;

final class MultiArrayDimFetchVariableVariables
{
    public function run()
    {
        $foo = 'a';
        $a = [
            'bar' => [
                'baz' => 'test',
            ],
        ];

        echo $$foo['bar']['baz'];
    }
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\Variable\WrapVariableVariableNameInCurlyBracesRector\Fixture;

final class MultiArrayDimFetchVariableVariables
{
    public function run()
    {
        $foo = 'a';
        $a = [
            'bar' => [
                'baz' => 'test',
            ],
        ];

        echo ${$foo['bar']}['baz'];
    }
}

?>
