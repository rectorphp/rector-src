<?php

namespace Rector\Tests\Php74\Rector\If_\IfToNullCoalescingAssignRector\Fixture;

class IssetNullableProperty
{
    private ?array $values = null;

    public function get(): array
    {
        if (! isset($this->values)) {
            $this->values = ['default'];
        }

        return $this->values;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\If_\IfToNullCoalescingAssignRector\Fixture;

class IssetNullableProperty
{
    private ?array $values = null;

    public function get(): array
    {
        $this->values ??= ['default'];

        return $this->values;
    }
}

?>
