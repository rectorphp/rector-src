<?php

namespace Rector\Tests\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector\Fixture;

class NamedArgumentForMessage
{
    public function run()
    {
		try {
            $this->run();
        }catch(\Throwable $throwable) {
            throw new LogicException(message: 'Some exception');
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector\Fixture;

class NamedArgumentForMessage
{
    public function run()
    {
		try {
            $this->run();
        }catch(\Throwable $throwable) {
            throw new LogicException(message: 'Some exception', code: $throwable->getCode(), previous: $throwable);
        }
    }
}

?>
