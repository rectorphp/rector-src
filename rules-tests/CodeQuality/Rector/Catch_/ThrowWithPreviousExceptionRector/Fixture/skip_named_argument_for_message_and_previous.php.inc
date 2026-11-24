<?php

namespace Rector\Tests\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector\Fixture;

use LogicException;

class SkipNamedArgumentForMessageAndPrevious
{
    public function run()
    {
        try {
            $this->run();
        }catch(\Throwable $throwable) {
            throw new LogicException(message: 'Some exception', previous: $throwable);
        }
    }
}
