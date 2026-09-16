<?php

namespace Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Fixture;

use Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Source\SomeObject;

final class IdenticalNullFallback
{
    public function run(?SomeObject $someObject): ?string
    {
        return null === $someObject ? null : $someObject->getName();
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Fixture;

use Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Source\SomeObject;

final class IdenticalNullFallback
{
    public function run(?SomeObject $someObject): ?string
    {
        return $someObject?->getName();
    }
}

?>
