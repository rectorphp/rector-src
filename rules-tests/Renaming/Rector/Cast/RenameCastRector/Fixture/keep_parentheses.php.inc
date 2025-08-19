<?php

namespace Rector\Tests\Renaming\Rector\Cast\RenameCastRector\Fixture;

final class KeepParentheses
{
    public function runInt(float $i): int
    {
        return (int) ($i * $i);
    }

    public function runInteger(float $i): int
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
    public function runInt(float $i): int
    {
        return (int) ($i * $i);
    }

    public function runInteger(float $i): int
    {
        return (int) ($i * $i);
    }
}

?>