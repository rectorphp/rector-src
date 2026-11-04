<?php

namespace Rector\Tests\Php55\Rector\FuncCall\PregReplaceEModifierRector\Fixture;

class SkipInterpolatedPatternNoEModifier
{
    public function run($str)
    {
        $pattern = '\\d+';

        $str = preg_replace("/$pattern/i", "str_repeat('#', \\1)", $str);
        $str = preg_replace("/e$pattern/", "str_repeat('#', \\1)", $str);
        $str = preg_replace("/$pattern/$modifiers", "str_repeat('#', \\1)", $str);

        return $str;
    }
}
