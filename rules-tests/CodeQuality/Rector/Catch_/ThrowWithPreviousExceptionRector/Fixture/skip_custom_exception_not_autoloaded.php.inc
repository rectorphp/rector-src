<?php

namespace Rector\Tests\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector\Fixture;

use CustomException;
use Throwable;

class SkipCustomExceptionNotAutoloaded
{
    public function run()
    {
        try {

        } catch (Throwable $exception) {
            if (true) {
                throw new CustomException;
            }
            handleException($exception);
        }
    }
}

?>
