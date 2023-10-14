<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

class KnownOffset
{
    public const DEFAULT_GROUP = 'default';

    /**
     * @var array<string, list<self>>
     */
    private static $groups = [];

    public static function knownOffset(): array
    {
        $group = 'default';

        self::$groups[$group] = ["foo"];

        if (! empty(self::$groups[$group])) {
            foreach (self::$groups[$group] as $group) {
                echo "hello";
            }
        }

        return [];
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

class KnownOffset
{
    public const DEFAULT_GROUP = 'default';

    /**
     * @var array<string, list<self>>
     */
    private static $groups = [];

    public static function knownOffset(): array
    {
        $group = 'default';

        self::$groups[$group] = ["foo"];

        foreach (self::$groups[$group] as $group) {
            echo "hello";
        }

        return [];
    }
}

?>
