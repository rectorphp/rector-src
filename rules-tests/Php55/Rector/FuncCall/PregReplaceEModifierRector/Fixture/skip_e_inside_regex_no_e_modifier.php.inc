<?php

namespace Rector\Tests\Php55\Rector\FuncCall\PregReplaceEModifierRector\Fixture;

function clean_link($a)
{
    return "change";
}

function run($str)
{
    $eval_links = '$link = clean_link("$0")';

    return preg_replace('{test}i',"$eval_links",$str);
}

