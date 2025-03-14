<?php

namespace Rector\Tests\Php84\Rector\FuncCall\AddEscapeArgumentRector;

final class SkipPassEscape
{
    public function run($a, $b, $c, $d)
    {
        str_getcsv($a, $b, $c, $d);
    }
}
