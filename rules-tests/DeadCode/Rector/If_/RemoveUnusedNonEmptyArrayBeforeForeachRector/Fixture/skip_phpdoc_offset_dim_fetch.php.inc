<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

class SkipPhpdocOffset
{
    /**
     * @param array{default: foo} $array
     */
    public static function knownOffset($array): array
    {
        $group = 'default';

        if (! empty($array[$group])) {
            foreach ($array[$group] as $groupValue) {
                echo "hello";
            }
        }

        return [];
    }
}

?>
