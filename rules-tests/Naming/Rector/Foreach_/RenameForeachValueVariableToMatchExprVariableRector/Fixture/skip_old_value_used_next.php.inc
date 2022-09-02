<?php

namespace Rector\Tests\Naming\Rector\Foreach_\RenameForeachValueVariableToMatchExprVariableRector\Fixture;

final class SkipOldValueUsedNext
{
    public function run()
    {
        $numbers = ['one', 'two'];

        foreach ($numbers as $txt) {
            echo "This is number ".$txt;
        }

        echo $txt;
    }
}