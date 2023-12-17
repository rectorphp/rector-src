<?php

namespace Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRectorTest\Fixture;

final class SkipGlobalVariable
{
    public function run()
    {
        return empty($_SESSION);
    }
}
