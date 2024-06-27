<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\FixtureAlwaysSprintf;

final class IncludeNumbers
{
    public function run(int $number)
    {
        return "value {$number}";
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\FixtureAlwaysSprintf;

final class IncludeNumbers
{
    public function run(int $number)
    {
        return sprintf('value %d', $number);
    }
}

?>
