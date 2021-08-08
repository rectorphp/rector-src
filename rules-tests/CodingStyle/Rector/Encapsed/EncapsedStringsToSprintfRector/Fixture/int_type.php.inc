<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\Fixture;

final class IntType
{
    public function run(array $array, string $item)
    {
        $value = count($array);
        return "We have {$value} {$item}";
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\Fixture;

final class IntType
{
    public function run(array $array, string $item)
    {
        $value = count($array);
        return sprintf('We have %d %s', $value, $item);
    }
}

?>
