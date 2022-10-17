<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanAnd\SimplifyEmptyArrayCheckRector\Fixture;

final class SkipRightEmptyExprNotEqualLeftIsArray
{
    public function run( $a )
    {
        $random = rand( 0, 10 );
        $x = !empty( $a['x'] ) ? $a['x'] : array();
        $y = !empty( $a['y'] ) ? $a['y'] : array();

        if (is_array( $x ) && empty( $random ) ) {
            echo "hello";
        }
    }
}
