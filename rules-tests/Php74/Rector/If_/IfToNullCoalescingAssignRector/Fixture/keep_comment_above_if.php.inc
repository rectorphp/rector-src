<?php

namespace Rector\Tests\Php74\Rector\If_\IfToNullCoalescingAssignRector\Fixture;

/**
 * Fallback value description
 *
 * @var int $limit
 */
if (! isset($limit)) {
    $limit = 100;
}

// simple comment
if (! isset($name)) {
    $name = 'default';
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\If_\IfToNullCoalescingAssignRector\Fixture;

/**
 * Fallback value description
 *
 * @var int $limit
 */
$limit ??= 100;

// simple comment
$name ??= 'default';

?>
