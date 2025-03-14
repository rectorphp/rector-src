<?php

namespace Rector\Tests\Php84\Rector\FuncCall\AddEscapeArgumentRector;

final class SkipPassEscapeNamed
{
    public function run($a, $b)
    {
        str_getcsv($a, $b, escape: '\\');
    }
}
