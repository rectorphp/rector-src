<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

abstract class ThisClass
{
    public function run()
    {
        return $this::class == 'SomeClass';
    }

    public function run2()
    {
        return 'SomeClass' == $this::class;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

abstract class ThisClass
{
    public function run()
    {
        return $this::class === 'SomeClass';
    }

    public function run2()
    {
        return 'SomeClass' === $this::class;
    }
}

?>
