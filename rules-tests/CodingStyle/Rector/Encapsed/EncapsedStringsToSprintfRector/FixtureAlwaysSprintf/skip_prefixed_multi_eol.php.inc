<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\FixtureAlwaysSprintf;

final class SkipPrefixedMultiEol
{
    public function run(string $format)
    {
        return "prefix {$format}\n\n";
    }
}
