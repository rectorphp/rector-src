<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanOr\RepeatedOrEqualToInArrayRector\Fixture;

final class CompareSameVariable
{
    public function demo($someVariable): bool
    {
        if ($someVariable === 'a' || $someVariable === 'b' || $someVariable === 'c') {
            return true;
        }

        return false;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanOr\RepeatedOrEqualToInArrayRector\Fixture;

final class CompareSameVariable
{
    public function demo($someVariable): bool
    {
        if (in_array($someVariable, ['a', 'b', 'c'], true)) {
            return true;
        }

        return false;
    }
}

?>
