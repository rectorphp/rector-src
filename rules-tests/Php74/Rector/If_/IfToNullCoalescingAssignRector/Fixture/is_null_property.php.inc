<?php

namespace Rector\Tests\Php74\Rector\If_\IfToNullCoalescingAssignRector\Fixture;

class IsNullProperty
{
    private $instance;

    public function get()
    {
        if (is_null($this->instance)) {
            $this->instance = new self();
        }

        return $this->instance;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\If_\IfToNullCoalescingAssignRector\Fixture;

class IsNullProperty
{
    private $instance;

    public function get()
    {
        $this->instance ??= new self();

        return $this->instance;
    }
}

?>
