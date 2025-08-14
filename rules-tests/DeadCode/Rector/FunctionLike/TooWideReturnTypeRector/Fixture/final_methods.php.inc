<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\RemoveUnusedReturnTypeRector\Fixture;

class NonFinalClassWithFinalMethods
{
    public function getData(): string|int|\DateTime
    {
        return 'hello';
    }

    final public function getInfo(): string|int|bool
    {
        return 'info';
    }

    final protected function processData(): string|array|object
    {
        return 'processed';
    }

    private function helper(): string|int|float
    {
        return 'helper';
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\RemoveUnusedReturnTypeRector\Fixture;

class NonFinalClassWithFinalMethods
{
    public function getData(): string|int|\DateTime
    {
        return 'hello';
    }

    final public function getInfo(): string
    {
        return 'info';
    }

    final protected function processData(): string
    {
        return 'processed';
    }

    private function helper(): string
    {
        return 'helper';
    }
}

?>
