<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

final class SkipMultipleConstDifferentTypes
{
    public const TYPE = 'some_type', OTHER_TYPE = 1;
}