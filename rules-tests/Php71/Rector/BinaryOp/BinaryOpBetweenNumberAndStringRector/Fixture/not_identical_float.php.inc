<?php

namespace Rector\Tests\Php71\Rector\BinaryOp\BinaryOpBetweenNumberAndStringRector\Fixture;

class NotIdenticalFloat
{
    function foo(): ?float
    {
        return 0;
    }

    public function run()
    {
        $data = $this->foo();
        if (! is_null($data)) {
            var_dump($data !== '');
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php71\Rector\BinaryOp\BinaryOpBetweenNumberAndStringRector\Fixture;

class NotIdenticalFloat
{
    function foo(): ?float
    {
        return 0;
    }

    public function run()
    {
        $data = $this->foo();
        if (! is_null($data)) {
            var_dump($data !== 0.0);
        }
    }
}

?>
