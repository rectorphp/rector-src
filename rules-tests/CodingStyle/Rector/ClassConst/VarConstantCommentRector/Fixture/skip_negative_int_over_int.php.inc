<?php

namespace Rector\Tests\CodingStyle\Rector\ClassConst\VarConstantCommentRector\Fixture;

final class SkipNegativeIntOverInt
{
    /** @var negative-int */
    public const POSITIVE_INT = -5;
}
