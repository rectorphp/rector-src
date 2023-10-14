<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

class SkipPhpdocOffset
{
    public const DEFAULT_GROUP = 'default';

    /**
     * @var array<string, list<self>>
     */
    private static $groups = [];

    /**
     * @param array{default: foo} $array
     * @return array
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
