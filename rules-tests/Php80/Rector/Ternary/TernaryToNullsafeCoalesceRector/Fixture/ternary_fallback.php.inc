<?php

namespace Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Fixture;

use Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Source\SomeObject;

final class TernaryFallback
{
    public function property(?SomeObject $someObject, bool $flag): string
    {
        return $someObject !== null ? $someObject->name : ($flag ? 'first' : 'second');
    }

    public function method(?SomeObject $someObject, bool $flag): string
    {
        return $someObject !== null ? $someObject->getName() : ($flag ? 'first' : 'second');
    }

    public function identical(?SomeObject $someObject, string $fallback): string
    {
        return null === $someObject ? ($fallback ?: 'default') : $someObject->name;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Fixture;

use Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Source\SomeObject;

final class TernaryFallback
{
    public function property(?SomeObject $someObject, bool $flag): string
    {
        return $someObject->name ?? ($flag ? 'first' : 'second');
    }

    public function method(?SomeObject $someObject, bool $flag): string
    {
        return $someObject?->getName() ?? ($flag ? 'first' : 'second');
    }

    public function identical(?SomeObject $someObject, string $fallback): string
    {
        return $someObject->name ?? ($fallback ?: 'default');
    }
}

?>
