<?php

namespace Rector\Tests\Php55\Rector\FuncCall\PregReplaceEModifierRector\Fixture;

class SkipNoModifier
{
    public function run()
    {
        echo preg_replace('([[:upper:]]+)', '_$0', 'FOO');
    }
}
