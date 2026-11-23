<?php

namespace Rector\Tests\Php71\Rector\List_\ListToArrayDestructRector\Fixture;

final class PartialDestruct
{
    public function modify_shipping_rate_cost($cost, $rate) {
       list(, $instance_id) = explode(':', $rate->id);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php71\Rector\List_\ListToArrayDestructRector\Fixture;

final class PartialDestruct
{
    public function modify_shipping_rate_cost($cost, $rate) {
       [, $instance_id] = explode(':', $rate->id);
    }
}

?>
