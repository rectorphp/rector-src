<?php

namespace Rector\Tests\Transform\Rector\Scalar\ScalarValueToConstFetchRector\Source;

class ClassWithConst
{
    public const FOOBAR_INT = 10;

    public const FOOBAR_STRING = 'ABC';

    public const FOOBAR_FLOAT = 10.1;
}
