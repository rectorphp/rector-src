<?php

namespace Rector\Tests\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector\Fixture;

class NamedArgumentPrevious
{
    public function run()
    {
		try {
        	throw new \Exception('foo');
        } catch (\Throwable $e) {
        	throw new \RuntimeException(previous: $e);
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector\Fixture;

class NamedArgumentPrevious
{
    public function run()
    {
		try {
        	throw new \Exception('foo');
        } catch (\Throwable $e) {
        	throw new \RuntimeException(message: $e->getMessage(), code: $e->getCode(), previous: $e);
        }
    }
}

?>
