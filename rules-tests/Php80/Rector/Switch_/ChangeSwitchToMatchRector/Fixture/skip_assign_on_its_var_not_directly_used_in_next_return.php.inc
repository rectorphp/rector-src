<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class SkipAssignOnItsVarNotDirectlyUsedInNextReturn
{
    public const STATUS_B = 'b';
    public const STATUS_C = 'c';

    public function run(): \Closure
    {
        return static function (self $item) {
            $class = [];
            switch ($item->status) {
                    case self::STATUS_B:
                    case self::STATUS_C:
                            $class[] = 'red';
                            break;
            }

            return execute($class);
        };
    }
}
