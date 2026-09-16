<?php

namespace Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Fixture;

use Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Source\SomeObject;

final class NullFallback
{
    public function run(?SomeObject $someObject): array
    {
        $name = null !== $someObject ? $someObject->getName() : null;
        $property = $someObject !== null ? $someObject->name : null;

        return [$name, $property];
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Fixture;

use Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Source\SomeObject;

final class NullFallback
{
    public function run(?SomeObject $someObject): array
    {
        $name = $someObject?->getName();
        $property = $someObject?->name;

        return [$name, $property];
    }
}

?>
