<?php

namespace Rector\Tests\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector\Fixture;

class SkipNamedArgument
{
    public function run()
    {
		try {
            $this->run();
        }catch(\Throwable $throwable) {
            throw new \LogicException('Some exception', previous: $throwable);
        }
    }
}
