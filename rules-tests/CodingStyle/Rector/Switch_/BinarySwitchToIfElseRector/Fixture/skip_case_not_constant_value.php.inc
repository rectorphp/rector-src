<?php

namespace Rector\Tests\CodingStyle\Rector\Switch_\BinarySwitchToIfElseRector\Fixture;

final class SkipCaseNotConstantValue
{
    public function run(string $column_name)
    {
        switch ( $column_name ) {
          case in_array( $column_name, array( 'sku', 'product_name' ), true ):
            $item_data = '<span class="na">&ndash;</span>';
            break;

          default:
            $item_data = '';
        }

        return $item_data;
    }
}
