<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\FixtureAlwaysSprintf;

final class Newline
{
    public function run(string $format)
    {
        $result = "${format}\n";

        $nextResult = "\n${format}";
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\FixtureAlwaysSprintf;

final class Newline
{
    public function run(string $format)
    {
        $result = $format . PHP_EOL;

        $nextResult = PHP_EOL . $format;
    }
}

?>
