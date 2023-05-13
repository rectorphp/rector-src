<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SimplifyStrposLowerRector\Fixture;

final class Strpos
{
    public function run()
    {
        $string = 'hey';
        strpos(strtolower($string), 'find-me');

        $funcName = 'strpos';
        $funcName(strtolower($string), 'find-me');
    }
}

?>
