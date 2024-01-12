<?php

namespace Rector\Tests\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector\Fixture;

class Fixture
{
    public function run(array $values)
    {
        usort($values, [$this, 'compareSize']);

        return $values;
    }

    private function compareSize(int $first, $second): bool
    {
        return $first <=> $second;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector\Fixture;

class Fixture
{
    public function run(array $values)
    {
        usort($values, fn(int $first, $second): bool => $this->compareSize($first, $second));

        return $values;
    }

    private function compareSize(int $first, $second): bool
    {
        return $first <=> $second;
    }
}

?>
