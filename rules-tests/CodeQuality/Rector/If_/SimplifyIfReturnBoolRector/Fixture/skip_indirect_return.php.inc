<?php

namespace Rector\Tests\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector\Fixture;

final class SkipIndirectReturn
{
    public function before($var): bool
    {
        if( ( $_SERVER[ 'a' ] ?? '' ) !== 'const string' )
        {
            return false;
        }

        if( ( $_SERVER[ 'a' ] ?? '' ) !== $foo )
        {
            return false;
        }

        return random_int(1,2)==1;
    }
}
