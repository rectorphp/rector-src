<?php

namespace Rector\Tests\Php70\Rector\Variable\WrapVariableVariableNameInCurlyBracesRector\Fixture;

final class SkipWrappedArrayDimFetchVariableVariables
{
    public function run()
    {
        $baz = 'bat';
        $foo = array('bar' => 'baz');

        echo ${$foo['bar']};
    }
}
