<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

final class SkipMultipleConst
{
    public const TYPE = 'some_type', OTHER_TYPE = 1;
}