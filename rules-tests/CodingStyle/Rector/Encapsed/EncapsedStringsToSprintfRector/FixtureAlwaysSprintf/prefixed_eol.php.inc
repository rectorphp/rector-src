<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\FixtureAlwaysSprintf;

final class PrefixedEol
{
    public function run(string $format)
    {
        return "prefix $format\n";
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\FixtureAlwaysSprintf;

final class PrefixedEol
{
    public function run(string $format)
    {
        return sprintf('prefix %s%s', $format, PHP_EOL);
    }
}

?>
