<?php

namespace Rector\Tests\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector\Fixture;

use LogicException;

class NamedArgumentForMessageAndPrevious
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

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector\Fixture;

use LogicException;

class NamedArgumentForMessageAndPrevious
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
