<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\FixtureAlwaysSprintf;

final class EncapsedStringsToSprintfShouldEscapePercent
{
    public function run(string $value)
    {
        return "$value%";
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\FixtureAlwaysSprintf;

final class EncapsedStringsToSprintfShouldEscapePercent
{
    public function run(string $value)
    {
        return sprintf('%s%%', $value);
    }
}

?>
