<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class SkipDoWhileAlwaysReturned
{
    public function run(int $i)
    {
		do {
    		if (rand(0,1)) {
                return 1;
    		}

		    return 2;
		} while (++$i < 1);
    }
}
