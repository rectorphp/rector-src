<?php

namespace Rector\Tests\Privatization\Rector\ClassConst\PrivatizeFinalClassConstantRector\Fixture;

/**
 * @final
 */
class SkipFinalDocblock
{
    protected const SOME_CONSTANT = 'some-value';
}
