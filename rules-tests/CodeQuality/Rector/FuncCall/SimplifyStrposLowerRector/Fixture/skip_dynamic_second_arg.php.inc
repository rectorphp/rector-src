<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SimplifyStrposLowerRector\Fixture;

final class SkipDynamicSecondArg
{
    public function run(string $findMe)
    {
        $string = 'hey';
        strpos(strtolower($string), $findMe);
    }
}
