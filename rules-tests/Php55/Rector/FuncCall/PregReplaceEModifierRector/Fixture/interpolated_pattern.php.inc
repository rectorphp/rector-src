<?php

namespace Rector\Tests\Php55\Rector\FuncCall\PregReplaceEModifierRector\Fixture;

class InterpolatedPattern
{
    public function run($str)
    {
        $pattern = '\\d+';

        $str = preg_replace("/$pattern/e", "str_repeat('#', \\1)", $str);
        $str = preg_replace("#({$pattern})#ie", "strtoupper('\\1')", $str);

        return $str;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\FuncCall\PregReplaceEModifierRector\Fixture;

class InterpolatedPattern
{
    public function run($str)
    {
        $pattern = '\\d+';

        $str = preg_replace_callback("/$pattern/", function ($matches) {
            return str_repeat('#', $matches[1]);
        }, $str);
        $str = preg_replace_callback("#({$pattern})#i", function ($matches) {
            return strtoupper($matches[1]);
        }, $str);

        return $str;
    }
}

?>
