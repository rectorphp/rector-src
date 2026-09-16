<?php

namespace Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Fixture;

use Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Source\SomeObject;

final class IdenticalNullFallback
{
    public function run(?SomeObject $someObject): array
    {
        $name = null === $someObject ? null : $someObject->getName();
        $property = $someObject === null ? null : $someObject->name;

        return [$name, $property];
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Fixture;

use Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Source\SomeObject;

final class IdenticalNullFallback
{
    public function run(?SomeObject $someObject): array
    {
        $name = $someObject?->getName();
        $property = $someObject?->name;

        return [$name, $property];
    }
}

?>
