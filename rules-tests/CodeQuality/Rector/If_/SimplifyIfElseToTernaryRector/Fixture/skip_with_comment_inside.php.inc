<?php

namespace Rector\Tests\CodeQuality\Rector\If_\SimplifyIfElseToTernaryRector\Fixture;

class SkipWithCommentInside
{
    public function run()
    {
        if ( $user_id === 0 ) {
            // some important comment
            $access = true;
        } else {
            // some another important comment
            $access = false;
        }
    }
}
