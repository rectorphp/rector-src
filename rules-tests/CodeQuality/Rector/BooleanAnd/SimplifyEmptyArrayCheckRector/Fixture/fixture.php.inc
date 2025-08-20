<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanAnd\SimplifyEmptyArrayCheckRector\Fixture;

final class Fixture
{
    public function isEmptyArray($values): bool
    {
        return is_array($values) && empty($values);
    }

    public function isEmptyArray2($values): bool
    {
        return empty($values) && is_array($values);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanAnd\SimplifyEmptyArrayCheckRector\Fixture;

final class Fixture
{
    public function isEmptyArray($values): bool
    {
        return $values === [];
    }

    public function isEmptyArray2($values): bool
    {
        return $values === [];
    }
}

?>
