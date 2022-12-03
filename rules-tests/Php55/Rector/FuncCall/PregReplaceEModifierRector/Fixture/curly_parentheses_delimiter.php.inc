<?php

namespace Rector\Tests\Php55\Rector\FuncCall\PregReplaceEModifierRector\Fixture;

// https://3v4l.org/fqdPZ#v5.4.45 vs https://3v4l.org/Zm16C#v5.5.38
function clean_link($a)
{
    return "change";
}

function run($str)
{
    $eval_links = '$link = clean_link("$0")';

    return preg_replace('{test}ei',"$eval_links",$str);
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\FuncCall\PregReplaceEModifierRector\Fixture;

// https://3v4l.org/fqdPZ#v5.4.45 vs https://3v4l.org/Zm16C#v5.5.38
function clean_link($a)
{
    return "change";
}

function run($str)
{
    $eval_links = '$link = clean_link("$0")';

    return preg_replace_callback('{test}i',function ($matches) use ($eval_links) {
        return $link = clean_link($matches[0]);
    },$str);
}

?>
