<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\Fixture;

final class JustValue
{
    public function testCommand(): void
    {
        $flag = 'hey';
        $expected = ["--${flag}"];
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\Fixture;

final class JustValue
{
    public function testCommand(): void
    {
        $flag = 'hey';
        $expected = ['--' . $flag];
    }
}

?>
