<?php

namespace Rector\Tests\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector\Fixture;

class NamedArgument
{
    public function run()
    {
		try {
            $this->run();
        }catch(\Throwable $throwable) {
            throw new LogicException('Some exception', previous: $throwable);
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector\Fixture;

class NamedArgument
{
    public function run()
    {
		try {
            $this->run();
        }catch(\Throwable $throwable) {
            throw new LogicException('Some exception', $throwable->getCode(), previous: $throwable);
        }
    }
}

?>
