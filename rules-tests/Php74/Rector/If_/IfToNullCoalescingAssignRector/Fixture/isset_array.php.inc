<?php

namespace Rector\Tests\Php74\Rector\If_\IfToNullCoalescingAssignRector\Fixture;

$array = [];
if (! isset($array['user_id'])) {
    $array['user_id'] = 'value';
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\If_\IfToNullCoalescingAssignRector\Fixture;

$array = [];
$array['user_id'] ??= 'value';

?>
