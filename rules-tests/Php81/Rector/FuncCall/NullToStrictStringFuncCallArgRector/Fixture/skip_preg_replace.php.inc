<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class SkipPregReplace
{
    public static function run(&$items)
    {
        $pattern = "/(\d+')/";
        $replacement = '$1';
        $items = preg_replace($pattern, $replacement, $items);
    }
}
