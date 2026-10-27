<?php

namespace Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Fixture;

use Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Source\SomeObject;

final class PropertyCoalesceFallback
{
    public function run(?SomeObject $someObject): string
    {
        return null !== $someObject ? $someObject->name : 'fallback';
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Fixture;

use Rector\Tests\Php80\Rector\Ternary\TernaryToNullsafeCoalesceRector\Source\SomeObject;

final class PropertyCoalesceFallback
{
    public function run(?SomeObject $someObject): string
    {
        return $someObject->name ?? 'fallback';
    }
}

?>
