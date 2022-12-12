<?php

namespace Rector\Tests\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector\Fixture;

/**
 * @final
 */
class SkipFinalDocblock
{
    protected function someMethod()
    {
    }
}
