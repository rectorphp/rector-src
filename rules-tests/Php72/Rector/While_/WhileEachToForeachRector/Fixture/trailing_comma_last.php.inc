<?php

namespace Rector\Tests\Php72\Rector\While_\WhileEachToForeachRector\Fixture;

// ref https://3v4l.org/mTeIL#v7.1.31 vs https://3v4l.org/UJH9S
final class TrailingCommaLast
{
    public function run()
    {
        while(list($code_name,) = each($poster_xd))
        {
            $poster_xd[$code_name] = preg_replace($orig_word, $replacement_word, $poster_xd[$code_name]);
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php72\Rector\While_\WhileEachToForeachRector\Fixture;

// ref https://3v4l.org/mTeIL#v7.1.31 vs https://3v4l.org/UJH9S
final class TrailingCommaLast
{
    public function run()
    {
        foreach (array_keys($poster_xd) as $code_name) {
            $poster_xd[$code_name] = preg_replace($orig_word, $replacement_word, $poster_xd[$code_name]);
        }
    }
}

?>
