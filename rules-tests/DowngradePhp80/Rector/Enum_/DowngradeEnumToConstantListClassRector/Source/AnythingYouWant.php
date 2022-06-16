<?php

declare(strict_types=1);

namespace Rector\Tests\DowngradePhp80\Rector\Enum_\DowngradeEnumToConstantListClassRector\Source;

enum AnythingYouWant
{
    public const LEFT = 'left';

    public const TWO = 5;
}
