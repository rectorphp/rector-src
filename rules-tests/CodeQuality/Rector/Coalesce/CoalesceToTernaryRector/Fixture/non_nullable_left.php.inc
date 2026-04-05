<?php

namespace Rector\Tests\CodeQuality\Rector\Concat\JoinStringConcatRector\Fixture;

class NonNullableLeft
{
    public function run(string $name)
    {
        return $name ?? 'tom';
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Concat\JoinStringConcatRector\Fixture;

class NonNullableLeft
{
    public function run(string $name)
    {
        return $name ?: 'tom';
    }
}

?>
