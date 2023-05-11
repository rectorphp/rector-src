<?php

namespace Rector\Tests\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector\Fixture;

class SkipChangevariableNextJumpTry
{
    public function run()
    {
        if (rand(0, 1)) {
            try {
            } catch (SomeException $typoException) {
            }
        }

        if (isset($typoException)) {
            $this->verify($typoException);
            $this->verify2($typoException);
        }
    }
}