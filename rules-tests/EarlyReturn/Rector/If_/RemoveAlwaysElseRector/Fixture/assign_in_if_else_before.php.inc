<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\RemoveAlwaysElseRector\Fixture;

function dummy($can_order_products, $offset, $limit)
{
    if (!empty($can_order_products)) {
        if (!empty($limit)) {
            if (!empty($offset)) {
                $can_order_products = array_slice($can_order_products, $offset, $limit);
            } else {
                $can_order_products = array_slice($can_order_products, 0, $limit);
            }

            if (!empty($can_order_products)) {
                return $can_order_products;
            } else {
                return false;
            }
        } else {
            return $can_order_products;
        }
    }

    return false;
}

?>
-----
<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\RemoveAlwaysElseRector\Fixture;

function dummy($can_order_products, $offset, $limit)
{
    if (!empty($can_order_products)) {
        if (!empty($limit)) {
            if (!empty($offset)) {
                $can_order_products = array_slice($can_order_products, $offset, $limit);
            } else {
                $can_order_products = array_slice($can_order_products, 0, $limit);
            }

            if (!empty($can_order_products)) {
                return $can_order_products;
            } else {
                return false;
            }
        } else {
            return $can_order_products;
        }
    }

    return false;
}

?>
