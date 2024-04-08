<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class SkipDoWhileTrueAlwaysReturned
{
    public function run()
    {
		do {
            if (mt_rand() === 0) {
                return 'yay';
            }
        } while (true);
    }
}
