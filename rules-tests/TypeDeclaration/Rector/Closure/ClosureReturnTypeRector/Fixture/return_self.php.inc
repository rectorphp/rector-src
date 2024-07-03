<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector\Fixture;

class ReturnSelf
{
    public static function fromEvent() : self
    {
        return new self;
    }
}

final class UseReturnSelf
{
    public function run()
    {
        return
            array_map(function () {
                return ReturnSelf::fromEvent();
            }, ['event']);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector\Fixture;

class ReturnSelf
{
    public static function fromEvent() : self
    {
        return new self;
    }
}

final class UseReturnSelf
{
    public function run()
    {
        return
            array_map(function (): \Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector\Fixture\ReturnSelf {
                return ReturnSelf::fromEvent();
            }, ['event']);
    }
}

?>
