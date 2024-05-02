<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SimplifyStrposLowerRector\Fixture;

final class SkipHasUppercaseSecondArg
{
    public function run()
    {
        $string = 'hey';
        strpos(strtolower($string), 'findME1');
    }
}
