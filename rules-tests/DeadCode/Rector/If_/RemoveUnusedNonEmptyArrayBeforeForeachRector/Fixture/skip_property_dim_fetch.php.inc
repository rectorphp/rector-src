<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

class SkipPropertyDimFetch
{
    public const DEFAULT_GROUP = 'default';

    /**
     * @var array<string, list<self>>
     */
    private static $groups = [];

    public static function getFootnotes($group = self::DEFAULT_GROUP): array
    {
        if (! empty(self::$groups[$group])) {
            foreach (self::$groups[$group] as $note) {
                echo "hello";
            }
        }

        return [];
    }
}
