<?php

namespace Rector\Tests\Renaming\Rector\Cast\RenameCastRector\Fixture;

final class KeepParentheses
{
    public function run(float $i): int
    {
        return (integer) ($i * $i);
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Cast\RenameCastRector\Fixture;

final class KeepParentheses
{
    public function run(float $i): int
    {
        return (int) ($i * $i);
    }
}

?>