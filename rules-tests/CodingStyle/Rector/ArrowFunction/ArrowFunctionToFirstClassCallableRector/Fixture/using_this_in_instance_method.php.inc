<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionToFirstClassCallableRector\Fixture;

class UsingThisInInstanceMethod
{
    public function test(): callable
    {
        return fn () => $this->values();
    }

    public function values(): array
    {
        return [];
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionToFirstClassCallableRector\Fixture;

class UsingThisInInstanceMethod
{
    public function test(): callable
    {
        return $this->values(...);
    }

    public function values(): array
    {
        return [];
    }
}

?>
