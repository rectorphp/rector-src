<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\Fixture;

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

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\Fixture;

final class IncludeNumbers
{
    public function run(int $number)
    {
        return 'value ' . $number;
    }
}

?>
