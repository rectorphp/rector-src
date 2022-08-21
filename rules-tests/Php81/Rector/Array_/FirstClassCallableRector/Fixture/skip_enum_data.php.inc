<?php

namespace Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Fixture;

enum SkipEnumData: int {

    case FULLY_REGISTERED = 0x000001;
    case MEMBER = 0x000010;

    public static function getPublic(): array {
        return [
            self::FULLY_REGISTERED,
            self::MEMBER,
        ];
    }

}
