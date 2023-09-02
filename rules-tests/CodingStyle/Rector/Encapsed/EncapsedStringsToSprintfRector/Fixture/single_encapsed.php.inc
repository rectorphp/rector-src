<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\Fixture;

final class SingleEncapsed
{
    private $format = 'json';

    public function run(string $format)
    {
        return "{$this->format}";
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\Fixture;

final class SingleEncapsed
{
    private $format = 'json';

    public function run(string $format)
    {
        return $this->format;
    }
}

?>
