<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class SkipWhileTrueAlwaysReturned
{
    public function run()
    {
		while (true) {
            if (mt_rand() === 0) {
                return 'yay';
            }
        }
    }
}
