<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\FixturePhp74;

use Symfony\Component\Finder\Finder;

class SkipGetIterator
{
    public function run()
    {
        $finder = Finder::create()->files()
            ->in(__DIR__ . '/Source');

        $files = iterator_to_array($finder->getIterator());
    }
}
