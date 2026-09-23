<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanAnd\RepeatedAndNotEqualToNotInArrayRector\Fixture;

final class YodaNullOnVariables
{
    public function run($first, $second, $third): bool
    {
        if (null !== $first && null !== $second && null !== $third) {
            return true;
        }

        return false;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanAnd\RepeatedAndNotEqualToNotInArrayRector\Fixture;

final class YodaNullOnVariables
{
    public function run($first, $second, $third): bool
    {
        if (!in_array(null, [$first, $second, $third], true)) {
            return true;
        }

        return false;
    }
}

?>
