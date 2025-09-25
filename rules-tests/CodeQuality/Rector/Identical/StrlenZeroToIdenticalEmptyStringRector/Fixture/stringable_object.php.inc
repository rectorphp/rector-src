<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\StrlenZeroToIdenticalEmptyStringRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Identical\StrlenZeroToIdenticalEmptyStringRector\Source\Stringable;

class StringableObject
{
    public function run(Stringable $value)
    {
        $empty = strlen($value) === 0;
        $empty = 0 === strlen($value);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\StrlenZeroToIdenticalEmptyStringRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Identical\StrlenZeroToIdenticalEmptyStringRector\Source\Stringable;

class StringableObject
{
    public function run(Stringable $value)
    {
        $empty = (string) $value === '';
        $empty = (string) $value === '';
    }
}

?>
