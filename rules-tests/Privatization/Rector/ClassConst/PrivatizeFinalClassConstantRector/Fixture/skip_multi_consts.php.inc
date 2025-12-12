<?php

declare(strict_types=1);

namespace Rector\Tests\Privatization\Rector\ClassConst\PrivatizeFinalClassConstantRector\Fixture;

final class SkipMultiConsts
{
    protected const FIRST_VAL = 'a', SECOND_VAL = 'b';
}
