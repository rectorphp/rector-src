<?php

namespace Rector\Tests\Php84\Rector\FuncCall\AddEscapeArgumentRector;

final class SkipPassEscapeNamed
{
    public function run($a, $b, $c)
    {
        str_getcsv($a, $b, $c, escape: '\\');

        $obj = new \SplFileObject();
        $obj->fputcsv($a, $b, $c, escape: '\\');
    }
}
